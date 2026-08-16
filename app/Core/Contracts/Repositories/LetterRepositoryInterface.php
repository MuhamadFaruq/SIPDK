<?php

namespace App\Core\Contracts\Repositories;

use App\Core\DTOs\LetterDTO;
use App\Models\Letter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface LetterRepositoryInterface
{
    public function getPaginatedLetters(array $filters, int $perPage = 10): LengthAwarePaginator;

    public function findById(int $id): ?Letter;

    public function generateNextAgendaNumber(): string;

    public function createLetter(LetterDTO $dto): Letter;

    public function updateLetter(Letter $letter, LetterDTO $dto): Letter;

    public function deleteLetter(Letter $letter): bool;

    public function getLatestLetters(int $limit = 6): Collection;

    public function getLettersForReport(string $startDate, string $endDate, ?int $categoryId = null, ?string $status = null): Collection;

    public function getCounts(): array;

    public function getDistinctYears(): Collection;
}
