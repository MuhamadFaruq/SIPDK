<?php

namespace App\Core\Contracts\Repositories;

use App\Core\DTOs\OutgoingLetterDTO;
use App\Models\OutgoingLetter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface OutgoingLetterRepositoryInterface
{
    public function getPaginatedLetters(array $filters, int $perPage = 10): LengthAwarePaginator;

    public function findById(int $id): ?OutgoingLetter;

    public function generateNextAgendaNumber(): string;

    public function createLetter(OutgoingLetterDTO $dto): OutgoingLetter;

    public function updateLetter(OutgoingLetter $letter, OutgoingLetterDTO $dto): OutgoingLetter;

    public function deleteLetter(OutgoingLetter $letter): bool;

    public function getLatestLetters(int $limit = 6): Collection;

    public function getCounts(): array;
}
