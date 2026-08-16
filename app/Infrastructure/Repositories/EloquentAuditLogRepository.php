<?php

namespace App\Infrastructure\Repositories;

use App\Core\Contracts\Repositories\AuditLogRepositoryInterface;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentAuditLogRepository implements AuditLogRepositoryInterface
{
    public function getPaginatedLogs(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = AuditLog::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('module', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['module'])) {
            $query->where('module', $filters['module']);
        }

        return $query->latest('created_at')->paginate($perPage)->withQueryString();
    }

    public function getLatestAudits(int $limit = 5): Collection
    {
        return AuditLog::latest('created_at')->take($limit)->get();
    }

    public function getDistinctModules(): Collection
    {
        return AuditLog::select('module')->distinct()->pluck('module');
    }

    public function record(?User $user, string $action, string $module, string $description = ''): AuditLog
    {
        return AuditLog::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'System',
            'role_name' => $user?->role?->display_name ?? 'Guest',
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'ip_address' => request()->ip(),
            'created_at' => now(),
        ]);
    }
}
