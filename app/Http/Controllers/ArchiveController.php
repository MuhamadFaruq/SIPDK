<?php

namespace App\Http\Controllers;

use App\Core\Contracts\Repositories\LetterRepositoryInterface;
use App\Models\LetterCategory;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function __construct(
        private readonly LetterRepositoryInterface $letterRepository,
    ) {}

    public function index(Request $request)
    {
        $letters = $this->letterRepository->getPaginatedLetters($request->all(), 12);
        $categories = LetterCategory::all();
        $years = $this->letterRepository->getDistinctYears();

        return view('archive.index', compact('letters', 'categories', 'years'));
    }
}
