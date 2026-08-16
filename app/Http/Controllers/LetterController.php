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

        return view('letters.print_agenda', compact('letter'));
    }
}
