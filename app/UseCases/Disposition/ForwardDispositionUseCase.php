<?php

namespace App\UseCases\Disposition;

use App\Core\Contracts\Repositories\AuditLogRepositoryInterface;
use App\Core\Contracts\Repositories\DispositionRepositoryInterface;
use App\Core\Contracts\Repositories\UserRepositoryInterface;
use App\Core\DTOs\DispositionDTO;
use App\Models\Disposition;
use App\Models\DispositionHistory;
use App\Models\Notification;

class ForwardDispositionUseCase
{
    public function __construct(
        private readonly DispositionRepositoryInterface $dispositionRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly AuditLogRepositoryInterface $auditLogRepository,
    ) {}

    public function execute(Disposition $parentDisposition, array $data, $forwarder): void
    {
        $letter = $parentDisposition->letter;
        $urgency = $data['urgency'] ?? $parentDisposition->urgency;
        $dueDate = $data['due_date'] ?? $parentDisposition->due_date?->format('Y-m-d');

        foreach ($data['recipients'] as $recipientId) {
            $recipient = $this->userRepository->findById($recipientId);

            $dto = new DispositionDTO(
                letterId: $letter->id,
                senderUserId: $forwarder->id,
                recipientUserId: $recipient->id,
                recipientDepartmentId: $recipient->department_id,
                instruction: $data['instruction'],
                urgency: $urgency,
                dueDate: $dueDate,
                parentId: $parentDisposition->id,
            );

            $newDisposition = $this->dispositionRepository->createDisposition($dto);

            // Record history in parent
            DispositionHistory::create([
                'disposition_id' => $parentDisposition->id,
                'user_id' => $forwarder->id,
                'action' => 'Diteruskan ke Staf',
                'notes' => "Diteruskan kepada {$recipient->name} dengan instruksi: {$data['instruction']}",
            ]);

            // Notification to recipient
            Notification::create([
                'user_id' => $recipient->id,
                'title' => 'Disposisi Terusan Diterima',
                'message' => "Disposisi surat {$letter->agenda_number} diteruskan kepada Anda oleh {$forwarder->name}.",
                'link' => route('dispositions.index'),
            ]);
        }

        // Update status of parent disposition to Diproses
        if ($parentDisposition->status === 'Menunggu') {
            $parentDisposition->update(['status' => 'Diproses']);
        }

        $this->auditLogRepository->record(
            $forwarder,
            'Meneruskan Disposisi',
            'Disposisi',
            "Meneruskan disposisi surat No. {$letter->agenda_number} kepada " . count($data['recipients']) . " staf/pelaksana."
        );
    }
}
