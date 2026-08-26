<?php

namespace App\Http\Controllers;

use App\Core\Contracts\Repositories\DispositionRepositoryInterface;
use App\Core\Contracts\Repositories\LetterRepositoryInterface;
use App\Core\Contracts\Repositories\UserRepositoryInterface;
use App\Http\Requests\FollowUpDispositionRequest;
use App\Http\Requests\ForwardDispositionRequest;
use App\Http\Requests\StoreDispositionRequest;
use App\Models\Department;
use App\Models\Disposition;
use App\Models\User;
use App\UseCases\Disposition\FollowUpDispositionUseCase;
use App\UseCases\Disposition\ForwardDispositionUseCase;
use App\UseCases\Disposition\SendDispositionUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DispositionController extends Controller
{
    public function __construct(
        private readonly DispositionRepositoryInterface $dispositionRepository,
        private readonly LetterRepositoryInterface $letterRepository,
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function index(Request $request)
    {
        $user = Auth::user();
        $tab = $request->get('tab', 'received');

        $dispositions = $this->dispositionRepository->getPaginatedDispositionsForUser($user, $tab, $request->all(), 10);
        $recipients = $this->userRepository->getActiveDispositionRecipients();

        return view('dispositions.index', compact('dispositions', 'tab', 'recipients'));
    }

    public function store(StoreDispositionRequest $request, SendDispositionUseCase $useCase)
    {
        $useCase->execute($request->validated(), Auth::user());
        return redirect()->back()->with('success', 'Disposisi berhasil dikirimkan!');
    }

    public function forward(ForwardDispositionRequest $request, Disposition $disposition, ForwardDispositionUseCase $useCase)
    {
        $user = Auth::user();
        if ($disposition->recipient_user_id !== $user->id && !$user->isAdmin() && !$user->isPimpinan()) {
            abort(403, 'Anda tidak memiliki hak meneruskan disposisi ini.');
        }

        $useCase->execute($disposition, $request->validated(), $user);
        return redirect()->back()->with('success', 'Disposisi berhasil diteruskan kepada staf/pelaksana.');
    }

    public function followUp(FollowUpDispositionRequest $request, Disposition $disposition, FollowUpDispositionUseCase $useCase)
    {
        $useCase->execute($disposition, $request->validated(), Auth::user());
        return redirect()->back()->with('success', 'Tindak lanjut disposisi berhasil disimpan.');
    }

    public function printSheet($letterId)
    {
        $letter = $this->letterRepository->findById((int) $letterId);
        if (!$letter) {
            abort(404, 'Surat tidak ditemukan.');
        }

        $user = Auth::user();
        if ($user->isPelaksana()) {
            $isAssigned = $letter->dispositions()->where('recipient_user_id', $user->id)->exists();
            if (!$isAssigned) {
                abort(403, 'Akses ditolak. Anda tidak memiliki akses mencetak lembar disposisi untuk surat ini.');
            }
        }

        $departments = Department::all();
        $lurahUser = User::whereHas('role', function ($q) {
            $q->where('name', 'pimpinan');
        })->orderBy('id', 'asc')->first(); // Pimpinan pertama (biasanya Lurah)

        return view('dispositions.print_sheet', compact('letter', 'departments', 'lurahUser'));
    }
}
