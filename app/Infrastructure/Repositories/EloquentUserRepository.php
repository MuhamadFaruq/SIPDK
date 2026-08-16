<?php

namespace App\Infrastructure\Repositories;

use App\Core\Contracts\Repositories\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function getPaginatedUsers(int $perPage = 15): LengthAwarePaginator
    {
        return User::with(['role', 'department'])->latest()->paginate($perPage);
    }

    public function findById(int $id): ?User
    {
        return User::with(['role', 'department'])->find($id);
    }

    public function getActiveDispositionRecipients(): Collection
    {
        return User::with('role', 'department')
            ->where('is_active', true)
            ->whereIn('role_id', function ($query) {
                $query->select('id')->from('roles')->whereIn('name', ['pimpinan', 'pelaksana']);
            })
            ->get();
    }

    public function createUser(array $data): User
    {
        return User::create($data);
    }

    public function updateUser(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }

    public function deleteUser(User $user): bool
    {
        return $user->delete();
    }
}
