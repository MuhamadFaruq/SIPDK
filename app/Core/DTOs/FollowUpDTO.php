<?php

namespace App\Core\DTOs;

class FollowUpDTO
{
    public function __construct(
        public readonly int $dispositionId,
        public readonly int $userId,
        public readonly string $status,
        public readonly string $followUpNotes,
    ) {}
}
