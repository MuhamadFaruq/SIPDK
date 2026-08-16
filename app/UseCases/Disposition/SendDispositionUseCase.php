<?php

namespace App\UseCases\Disposition;

use App\Core\Contracts\Repositories\AuditLogRepositoryInterface;
use App\Core\Contracts\Repositories\DispositionRepositoryInterface;
use App\Core\Contracts\Repositories\LetterRepositoryInterface;
use App\Core\Contracts\Repositories\UserRepositoryInterface;
use App\Core\DTOs\DispositionDTO;
use App\Models\Letter;
use App\Models\Notification;

class SendDispositionUseCase
{
    public function __construct(
        private readonly DispositionRepositoryInterface $dispositionRepository,
        private readonly LetterRepositoryInterface $letterRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly AuditLogRepositoryInterface $auditLogRepository,
    ) {}

    public function execute(array $data, $sender): void
    {
        $letter = $this->letterRepository->findById($data['letter_id']);

        foreach ($data['recipients'] as $recipientId) {
            $recipient = $this->userRepository->findById($recipientId);

            $dto = new DispositionDTO(
                letterId: $letter->id,
                senderUserId: $sender->id,
                recipientUserId: $recipient->id,
                recipientDepartmentId: $recipient->department_id,
                instruction: $data['instruction'],
                urgency: $data['urgency'],
                dueDate: $data['due_date'] ?? null,
            );

            $this->dispositionRepository->createDisposition($dto);

            // Create in-app notification
            Notification::create([
                'user_id' => $recipient->id,
                'title' => 'Disposisi Baru Diterima',
                'message' => "Anda menerima disposisi dari {$sender->name} untuk surat {$letter->agenda_number}.",
                'link' => route('dispositions.index'),
            ]);
        }

        // Update status letter to Didisposisi
        $letter->update(['status' => 'Didisposisi']);

        $this->auditLogRepository->record(
            $sender,
            'Memberikan Disposisi',
            'Disposisi',
            "Mengirimkan disposisi surat No. {$letter->agenda_number} kepada " . count($data['recipients']) . " penerima."
        );
    }
}
