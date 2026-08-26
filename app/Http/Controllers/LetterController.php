<?php

namespace App\Http\Controllers;

use App\Core\Contracts\Repositories\LetterRepositoryInterface;
use App\Core\Contracts\Repositories\UserRepositoryInterface;
use App\Http\Requests\StoreLetterRequest;
use App\Http\Requests\UpdateLetterRequest;
use App\Models\Department;
use App\Models\Letter;
use App\Models\LetterCategory;
use App\UseCases\Letter\DeleteLetterUseCase;
use App\UseCases\Letter\RegisterIncomingLetterUseCase;
use App\UseCases\Letter\UpdateLetterUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LetterController extends Controller
{
    public function __construct(
        private readonly LetterRepositoryInterface $letterRepository,
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->isPelaksana()) {
            return redirect()->route('dispositions.index');
        }

        $letters = $this->letterRepository->getPaginatedLetters($request->all(), 10);
        $categories = LetterCategory::all();

        return view('letters.index', compact('letters', 'categories'));
    }

    public function create()
    {
        $autoAgendaNumber = $this->letterRepository->generateNextAgendaNumber();
        $categories = LetterCategory::all();

        return view('letters.create', compact('autoAgendaNumber', 'categories'));
    }

    public function store(StoreLetterRequest $request, RegisterIncomingLetterUseCase $useCase)
    {
        $letter = $useCase->execute(
            $request->validated(),
            $request->file('letter_file'),
            Auth::user()
        );

        return redirect()->route('letters.show', $letter->id)->with('success', "Surat masuk No. Agenda {$letter->agenda_number} berhasil dicatat!");
    }

    public function show($id)
    {
        $letter = $this->letterRepository->findById((int) $id);
        if (!$letter) {
            abort(404, 'Surat tidak ditemukan.');
        }

        $user = Auth::user();
        // Standar SOP: Pelaksana hanya dapat melihat surat yang telah didisposisikan kepada dirinya
        if ($user->isPelaksana()) {
            $isAssigned = $letter->dispositions()->where('recipient_user_id', $user->id)->exists();
            if (!$isAssigned) {
                abort(403, 'Akses ditolak. Anda hanya dapat melihat rincian surat yang telah didisposisikan kepada Anda oleh pimpinan.');
            }
        }

        $recipients = $this->userRepository->getActiveDispositionRecipients();
        $departments = Department::all();

        return view('letters.show', compact('letter', 'recipients', 'departments'));
    }

    public function edit($id)
    {
        $letter = $this->letterRepository->findById((int) $id);
        if (!$letter) {
            abort(404, 'Surat tidak ditemukan.');
        }

        $categories = LetterCategory::all();

        return view('letters.edit', compact('letter', 'categories'));
    }

    public function update(UpdateLetterRequest $request, Letter $letter, UpdateLetterUseCase $useCase)
    {
        $useCase->execute(
            $letter,
            $request->validated(),
            $request->file('letter_file'),
            Auth::user()
        );

        return redirect()->route('letters.show', $letter->id)->with('success', 'Data surat berhasil diperbarui!');
    }

    public function destroy(Letter $letter, DeleteLetterUseCase $useCase)
    {
        $agendaNum = $letter->agenda_number;
        $useCase->execute($letter, Auth::user());

        return redirect()->route('letters.index')->with('success', "Surat agenda {$agendaNum} berhasil dihapus.");
    }

    public function printAgenda($id)
    {
        $letter = $this->letterRepository->findById((int) $id);
        if (!$letter) {
            abort(404, 'Surat tidak ditemukan.');
        }

        $user = Auth::user();
        if ($user->isPelaksana()) {
            abort(403, 'Akses ditolak. Lembar agenda persuratan hanya dapat dicetak oleh Administrator dan Pimpinan.');
        }

        return view('letters.print_agenda', compact('letter'));
    }

    public function file($id)
    {
        $letter = $this->letterRepository->findById((int) $id);
        if (!$letter || !$letter->file_path) {
            abort(404, 'Berkas dokumen tidak ditemukan.');
        }

        $user = Auth::user();
        if ($user->isPelaksana()) {
            $isAssigned = $letter->dispositions()->where('recipient_user_id', $user->id)->exists();
            if (!$isAssigned) {
                abort(403, 'Akses ditolak. Anda tidak memiliki izin mengakses berkas surat ini.');
            }
        }

        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($letter->file_path)) {
            abort(404, 'Berkas fisik tidak ditemukan di server.');
        }

        $fullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($letter->file_path);

        $mimeType = match($letter->file_type) {
            'pdf' => 'application/pdf',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            default => mime_content_type($fullPath) ?: 'application/octet-stream',
        };

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . addslashes($letter->file_name ?? basename($fullPath)) . '"',
        ]);
    }
}
