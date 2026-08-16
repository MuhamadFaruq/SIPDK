<?php

namespace App\UseCases\Report;

use App\Core\Contracts\Repositories\LetterRepositoryInterface;
use Illuminate\Support\Collection;

class GenerateReportUseCase
{
    public function __construct(
        private readonly LetterRepositoryInterface $letterRepository,
    ) {}

    public function execute(string $startDate, string $endDate, ?int $categoryId = null, ?string $status = null): array
    {
        $letters = $this->letterRepository->getLettersForReport($startDate, $endDate, $categoryId, $status);

        return [
            'letters' => $letters,
            'totalCount' => $letters->count(),
            'selesaiCount' => $letters->where('status', 'Selesai')->count(),
            'diprosesCount' => $letters->whereIn('status', ['Didisposisi', 'Diproses'])->count(),
            'baruCount' => $letters->where('status', 'Baru')->count(),
        ];
    }
}
