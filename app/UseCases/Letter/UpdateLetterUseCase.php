<?php

namespace App\UseCases\Letter;

use App\Core\Contracts\Repositories\AuditLogRepositoryInterface;
use App\Core\Contracts\Repositories\LetterRepositoryInterface;
use App\Core\Contracts\Services\FileStorageServiceInterface;
use App\Core\DTOs\LetterDTO;
use App\Models\Letter;

class UpdateLetterUseCase
{
    public function __construct(
        private readonly LetterRepositoryInterface $letterRepository,
        private readonly FileStorageServiceInterface $fileStorageService,
        private readonly AuditLogRepositoryInterface $auditLogRepository,
    ) {}

    public function execute(Letter $letter, array $requestData, $file = null, $user = null): Letter
    {
        $fileData = null;
        if ($file) {
            $this->fileStorageService->deleteFile($letter->file_path);
            $fileData = $this->fileStorageService->storeLetterFile($file);
        }

        $dto = LetterDTO::fromRequest($requestData, $fileData, $user?->id);
        $updatedLetter = $this->letterRepository->updateLetter($letter, $dto);

        $this->auditLogRepository->record(
            $user,
            'Memperbarui Surat',
            'Surat Masuk',
            "Memperbarui data surat agenda No. {$updatedLetter->agenda_number}"
        );

        return $updatedLetter;
    }
}
