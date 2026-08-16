<?php

namespace App\Core\Contracts\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function getPaginatedUsers(int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?User;

    public function getActiveDispositionRecipients(): Collection;

    public function createUser(array $data): User;

    public function updateUser(User $user, array $data): User;

    public function deleteUser(User $user): bool;
}
