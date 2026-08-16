<?php

namespace App\UseCases\Letter;

use App\Core\Contracts\Repositories\AuditLogRepositoryInterface;
use App\Core\Contracts\Repositories\LetterRepositoryInterface;
use App\Models\Letter;

class DeleteLetterUseCase
{
    public function __construct(
        private readonly LetterRepositoryInterface $letterRepository,
        private readonly AuditLogRepositoryInterface $auditLogRepository,
    ) {}

    public function execute(Letter $letter, $user = null): bool
    {
        $agendaNum = $letter->agenda_number;
        $deleted = $this->letterRepository->deleteLetter($letter);

        if ($deleted) {
            $this->auditLogRepository->record(
                $user,
                'Menghapus Surat',
                'Surat Masuk',
                "Menghapus (soft delete) surat agenda No. {$agendaNum}"
            );
        }

        return $deleted;
    }
}
