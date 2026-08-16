<?php

namespace App\Core\Contracts\Repositories;

use App\Core\DTOs\DispositionDTO;
use App\Core\DTOs\FollowUpDTO;
use App\Models\Disposition;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface DispositionRepositoryInterface
{
    public function getPaginatedDispositionsForUser(User $user, string $tab = 'received', array $filters = [], int $perPage = 10): LengthAwarePaginator;

    public function findById(int $id): ?Disposition;

    public function createDisposition(DispositionDTO $dto): Disposition;

    public function updateFollowUp(Disposition $disposition, FollowUpDTO $dto): Disposition;

    public function getPendingDispositionsForRecipient(int $userId): Collection;

    public function getSentDispositionsForSender(int $userId, int $limit = 5): Collection;

    public function countPendingDispositionsForLetter(int $letterId): int;
}
