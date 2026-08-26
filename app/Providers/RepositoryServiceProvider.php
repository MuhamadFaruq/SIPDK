<?php

namespace App\Providers;

use App\Core\Contracts\Repositories\AuditLogRepositoryInterface;
use App\Core\Contracts\Repositories\DispositionRepositoryInterface;
use App\Core\Contracts\Repositories\LetterRepositoryInterface;
use App\Core\Contracts\Repositories\UserRepositoryInterface;
use App\Core\Contracts\Services\FileStorageServiceInterface;
use App\Core\Contracts\Repositories\OutgoingLetterRepositoryInterface;
use App\Infrastructure\Repositories\EloquentAuditLogRepository;
use App\Infrastructure\Repositories\EloquentDispositionRepository;
use App\Infrastructure\Repositories\EloquentLetterRepository;
use App\Infrastructure\Repositories\EloquentOutgoingLetterRepository;
use App\Infrastructure\Repositories\EloquentUserRepository;
use App\Infrastructure\Storage\LocalFileStorageService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LetterRepositoryInterface::class, EloquentLetterRepository::class);
        $this->app->bind(OutgoingLetterRepositoryInterface::class, EloquentOutgoingLetterRepository::class);
        $this->app->bind(DispositionRepositoryInterface::class, EloquentDispositionRepository::class);
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(AuditLogRepositoryInterface::class, EloquentAuditLogRepository::class);
        $this->app->bind(FileStorageServiceInterface::class, LocalFileStorageService::class);
    }

    public function boot(): void
    {
        //
    }
}
