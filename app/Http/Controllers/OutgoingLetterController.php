<?php

namespace App\Http\Controllers;

use App\Core\Contracts\Repositories\OutgoingLetterRepositoryInterface;
use App\Http\Requests\StoreOutgoingLetterRequest;
use App\Http\Requests\UpdateOutgoingLetterRequest;
use App\Models\LetterCategory;
use App\Models\OutgoingLetter;
use App\UseCases\OutgoingLetter\DeleteOutgoingLetterUseCase;
use App\UseCases\OutgoingLetter\RegisterOutgoingLetterUseCase;
use App\UseCases\OutgoingLetter\UpdateOutgoingLetterUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OutgoingLetterController extends Controller
{
    public function __construct(
        private readonly OutgoingLetterRepositoryInterface $outgoingLetterRepository,
    ) {}

    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->isPelaksana()) {
            return redirect()->route('dispositions.index');
        }

        $letters = $this->outgoingLetterRepository->getPaginatedLetters($request->all(), 10);
        $categories = LetterCategory::all();

        return view('outgoing_letters.index', compact('letters', 'categories'));
    }

    public function create()
    {
        $autoAgendaNumber = $this->outgoingLetterRepository->generateNextAgendaNumber();
        $categories = LetterCategory::all();

        return view('outgoing_letters.create', compact('autoAgendaNumber', 'categories'));
    }

    public function store(StoreOutgoingLetterRequest $request, RegisterOutgoingLetterUseCase $useCase)
    {
        $letter = $useCase->execute(
            $request->validated(),
            $request->file('letter_file'),
            Auth::user()
        );

        return redirect()->route('outgoing-letters.show', $letter->id)
            ->with('success', "Surat keluar No. Agenda {$letter->agenda_number} berhasil dicatat!");
    }

    public function show($id)
    {
        $letter = $this->outgoingLetterRepository->findById((int) $id);
        if (!$letter) {
            abort(404, 'Surat keluar tidak ditemukan.');
        }

        return view('outgoing_letters.show', compact('letter'));
    }

    public function edit($id)
    {
        $letter = $this->outgoingLetterRepository->findById((int) $id);
        if (!$letter) {
            abort(404, 'Surat keluar tidak ditemukan.');
        }

        $categories = LetterCategory::all();

        return view('outgoing_letters.edit', compact('letter', 'categories'));
    }

    public function update(UpdateOutgoingLetterRequest $request, OutgoingLetter $outgoingLetter, UpdateOutgoingLetterUseCase $useCase)
    {
        $useCase->execute(
            $outgoingLetter,
            $request->validated(),
            $request->file('letter_file'),
            Auth::user()
        );

        return redirect()->route('outgoing-letters.show', $outgoingLetter->id)
            ->with('success', 'Data surat keluar berhasil diperbarui!');
    }

    public function destroy(OutgoingLetter $outgoingLetter, DeleteOutgoingLetterUseCase $useCase)
    {
        $agendaNum = $outgoingLetter->agenda_number;
        $useCase->execute($outgoingLetter, Auth::user());

        return redirect()->route('outgoing-letters.index')
            ->with('success', "Surat keluar agenda {$agendaNum} berhasil dihapus.");
    }

    public function printAgenda($id)
    {
        $letter = $this->outgoingLetterRepository->findById((int) $id);
        if (!$letter) {
            abort(404, 'Surat keluar tidak ditemukan.');
        }

        return view('outgoing_letters.print_agenda', compact('letter'));
    }

    public function file($id)
    {
        $letter = $this->outgoingLetterRepository->findById((int) $id);
        if (!$letter || !$letter->file_path) {
            abort(404, 'Berkas dokumen tidak ditemukan.');
        }

        $user = Auth::user();
        if ($user->isPelaksana()) {
            abort(403, 'Akses ditolak.');
        }

        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($letter->file_path)) {
            abort(404, 'Berkas fisik tidak ditemukan di server.');
        }

        $fullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($letter->file_path);

        $extension = strtolower(pathinfo($letter->file_path, PATHINFO_EXTENSION));
        $mimeType = match($extension) {
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
