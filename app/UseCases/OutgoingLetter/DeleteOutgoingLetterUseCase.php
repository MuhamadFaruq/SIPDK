<?php

namespace App\UseCases\OutgoingLetter;

use App\Core\Contracts\Repositories\AuditLogRepositoryInterface;
use App\Core\Contracts\Repositories\OutgoingLetterRepositoryInterface;
use App\Core\Contracts\Services\FileStorageServiceInterface;
use App\Models\OutgoingLetter;

class DeleteOutgoingLetterUseCase
{
    public function __construct(
        private readonly OutgoingLetterRepositoryInterface $outgoingLetterRepository,
        private readonly FileStorageServiceInterface $fileStorageService,
        private readonly AuditLogRepositoryInterface $auditLogRepository,
    ) {}

    public function execute(OutgoingLetter $letter, $user): bool
    {
        $agendaNum = $letter->agenda_number;

        if ($letter->file_path) {
            $this->fileStorageService->deleteFile($letter->file_path);
        }

        $deleted = $this->outgoingLetterRepository->deleteLetter($letter);

        $this->auditLogRepository->record(
            $user,
            'Menghapus Surat Keluar',
            'Surat Keluar',
            "Menghapus berkas surat keluar No. Agenda {$agendaNum}"
        );

        return $deleted;
    }
}
