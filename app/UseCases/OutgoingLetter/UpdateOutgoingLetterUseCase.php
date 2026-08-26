<?php

namespace App\UseCases\OutgoingLetter;

use App\Core\Contracts\Repositories\AuditLogRepositoryInterface;
use App\Core\Contracts\Repositories\OutgoingLetterRepositoryInterface;
use App\Core\Contracts\Services\FileStorageServiceInterface;
use App\Core\DTOs\OutgoingLetterDTO;
use App\Models\OutgoingLetter;

class UpdateOutgoingLetterUseCase
{
    public function __construct(
        private readonly OutgoingLetterRepositoryInterface $outgoingLetterRepository,
        private readonly FileStorageServiceInterface $fileStorageService,
        private readonly AuditLogRepositoryInterface $auditLogRepository,
    ) {}

    public function execute(OutgoingLetter $letter, array $requestData, $file, $user): OutgoingLetter
    {
        $fileData = null;
        if ($file) {
            if ($letter->file_path) {
                $this->fileStorageService->deleteFile($letter->file_path);
            }
            $fileData = $this->fileStorageService->storeLetterFile($file);
        }

        $dto = OutgoingLetterDTO::fromRequest($requestData, $fileData, $user->id);
        $updatedLetter = $this->outgoingLetterRepository->updateLetter($letter, $dto);

        $this->auditLogRepository->record(
            $user,
            'Memperbarui Surat Keluar',
            'Surat Keluar',
            "Mengubah data surat keluar No. Agenda {$updatedLetter->agenda_number}"
        );

        return $updatedLetter;
    }
}
