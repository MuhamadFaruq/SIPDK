<?php

namespace App\Core\DTOs;

class LetterDTO
{
    public function __construct(
        public readonly ?string $agendaNumber,
        public readonly string $referenceNumber,
        public readonly string $letterDate,
        public readonly string $receivedDate,
        public readonly string $sender,
        public readonly string $subject,
        public readonly ?string $summary,
        public readonly int $categoryId,
        public readonly string $degree,
        public readonly ?string $status = null,
        public readonly ?string $filePath = null,
        public readonly ?string $fileName = null,
        public readonly ?string $fileType = null,
        public readonly ?int $fileSize = null,
        public readonly ?int $createdBy = null,
    ) {}

    public static function fromRequest(array $data, ?array $fileData = null, ?int $userId = null): self
    {
        return new self(
            agendaNumber: $data['agenda_number'] ?? null,
            referenceNumber: $data['reference_number'],
            letterDate: $data['letter_date'],
            receivedDate: $data['received_date'],
            sender: $data['sender'],
            subject: $data['subject'],
            summary: $data['summary'] ?? null,
            categoryId: (int) $data['category_id'],
            degree: $data['degree'],
            status: $data['status'] ?? 'Baru',
            filePath: $fileData['file_path'] ?? null,
            fileName: $fileData['file_name'] ?? null,
            fileType: $fileData['file_type'] ?? null,
            fileSize: $fileData['file_size'] ?? null,
            createdBy: $userId
        );
    }
}
