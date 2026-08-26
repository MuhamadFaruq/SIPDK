<?php

namespace App\Core\DTOs;

class DispositionDTO
{
    public function __construct(
        public readonly int $letterId,
        public readonly int $senderUserId,
        public readonly int $recipientUserId,
        public readonly ?int $recipientDepartmentId,
        public readonly string $instruction,
        public readonly string $urgency,
        public readonly ?string $dueDate,
        public readonly ?int $parentId = null,
    ) {}
}
