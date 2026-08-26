<?php

namespace App\UseCases\Letter;

use App\Core\Contracts\Repositories\AuditLogRepositoryInterface;
use App\Core\Contracts\Repositories\LetterRepositoryInterface;
use App\Core\Contracts\Services\FileStorageServiceInterface;
use App\Core\DTOs\LetterDTO;
use App\Models\Letter;

class RegisterIncomingLetterUseCase
{
    public function __construct(
        private readonly LetterRepositoryInterface $letterRepository,
        private readonly FileStorageServiceInterface $fileStorageService,
        private readonly AuditLogRepositoryInterface $auditLogRepository,
    ) {}

    public function execute(array $requestData, $file, $user): Letter
    {
        $fileData = $file ? $this->fileStorageService->storeLetterFile($file) : null;
        $dto = LetterDTO::fromRequest($requestData, $fileData, $user->id);

        $letter = $this->letterRepository->createLetter($dto);

        $this->auditLogRepository->record(
            $user,
            'Menambahkan Surat',
            'Surat Masuk',
            "Mendaftarkan surat masuk baru No. Agenda {$letter->agenda_number} dari {$letter->sender}"
        );

        return $letter;
    }
}
