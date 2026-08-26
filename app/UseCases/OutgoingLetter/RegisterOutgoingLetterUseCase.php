<?php

namespace App\UseCases\OutgoingLetter;

use App\Core\Contracts\Repositories\AuditLogRepositoryInterface;
use App\Core\Contracts\Repositories\OutgoingLetterRepositoryInterface;
use App\Core\Contracts\Services\FileStorageServiceInterface;
use App\Core\DTOs\OutgoingLetterDTO;
use App\Models\OutgoingLetter;

class RegisterOutgoingLetterUseCase
{
    public function __construct(
        private readonly OutgoingLetterRepositoryInterface $outgoingLetterRepository,
        private readonly FileStorageServiceInterface $fileStorageService,
        private readonly AuditLogRepositoryInterface $auditLogRepository,
    ) {}

    public function execute(array $requestData, $file, $user): OutgoingLetter
    {
        $fileData = null;
        if ($file) {
            $fileData = $this->fileStorageService->storeLetterFile($file);
        }

        $dto = OutgoingLetterDTO::fromRequest($requestData, $fileData, $user->id);
        $letter = $this->outgoingLetterRepository->createLetter($dto);

        $this->auditLogRepository->record(
            $user,
            'Mencatat Surat Keluar',
            'Surat Keluar',
            "Mendaftarkan surat keluar baru No. Agenda {$letter->agenda_number} tujuan {$letter->destination}"
        );

        return $letter;
    }
}
