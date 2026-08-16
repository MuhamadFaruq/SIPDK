<?php

namespace App\UseCases\Disposition;

use App\Core\Contracts\Repositories\AuditLogRepositoryInterface;
use App\Core\Contracts\Repositories\DispositionRepositoryInterface;
use App\Core\DTOs\FollowUpDTO;
use App\Models\Disposition;

class FollowUpDispositionUseCase
{
    public function __construct(
        private readonly DispositionRepositoryInterface $dispositionRepository,
        private readonly AuditLogRepositoryInterface $auditLogRepository,
    ) {}

    public function execute(Disposition $disposition, array $data, $user): Disposition
    {
        $dto = new FollowUpDTO(
            dispositionId: $disposition->id,
            userId: $user->id,
            status: $data['status'],
            followUpNotes: $data['follow_up_notes']
        );

        $updatedDisposition = $this->dispositionRepository->updateFollowUp($disposition, $dto);

        // Update overall letter status
        if ($data['status'] === 'Selesai') {
            $pendingCount = $this->dispositionRepository->countPendingDispositionsForLetter($disposition->letter_id);
            if ($pendingCount === 0) {
                $disposition->letter->update(['status' => 'Selesai']);
            } else {
                $disposition->letter->update(['status' => 'Diproses']);
            }
        } elseif ($data['status'] === 'Diproses') {
            $disposition->letter->update(['status' => 'Diproses']);
        }

        $this->auditLogRepository->record(
            $user,
            'Tindak Lanjut Disposisi',
            'Disposisi',
            "Pembaruan status disposisi surat agenda {$disposition->letter->agenda_number} menjadi {$data['status']}"
        );

        return $updatedDisposition;
    }
}
