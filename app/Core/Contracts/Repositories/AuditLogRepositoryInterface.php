<?php

namespace App\Core\Contracts\Repositories;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface AuditLogRepositoryInterface
{
    public function getPaginatedLogs(array $filters = [], int $perPage = 20): LengthAwarePaginator;

    public function getLatestAudits(int $limit = 5): Collection;

    public function getDistinctModules(): Collection;

    public function record(?User $user, string $action, string $module, string $description = ''): AuditLog;
}
