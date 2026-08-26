<?php

namespace App\Infrastructure\Repositories;

use App\Core\Contracts\Repositories\DispositionRepositoryInterface;
use App\Core\DTOs\DispositionDTO;
use App\Core\DTOs\FollowUpDTO;
use App\Models\Disposition;
use App\Models\DispositionHistory;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentDispositionRepository implements DispositionRepositoryInterface
{
    public function getPaginatedDispositionsForUser(User $user, string $tab = 'received', array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Disposition::with(['letter.category', 'sender', 'recipient', 'recipientDepartment']);

        if ($tab === 'sent') {
            $query->where('sender_user_id', $user->id);
        } else {
            $query->where('recipient_user_id', $user->id);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['urgency'])) {
            $query->where('urgency', $filters['urgency']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function findById(int $id): ?Disposition
    {
        return Disposition::with(['letter', 'sender', 'recipient'])->find($id);
    }

    public function createDisposition(DispositionDTO $dto): Disposition
    {
        $disposition = Disposition::create([
            'letter_id' => $dto->letterId,
            'parent_id' => $dto->parentId,
            'sender_user_id' => $dto->senderUserId,
            'recipient_user_id' => $dto->recipientUserId,
            'recipient_department_id' => $dto->recipientDepartmentId,
            'instruction' => $dto->instruction,
            'urgency' => $dto->urgency,
            'due_date' => $dto->dueDate,
            'status' => 'Menunggu',
        ]);

        DispositionHistory::create([
            'disposition_id' => $disposition->id,
            'user_id' => $dto->senderUserId,
            'action' => 'Disposisi Dibuat',
            'notes' => "Instruksi: {$dto->instruction}",
        ]);

        return $disposition;
    }

    public function updateFollowUp(Disposition $disposition, FollowUpDTO $dto): Disposition
    {
        $disposition->update([
            'status' => $dto->status,
            'follow_up_notes' => $dto->followUpNotes,
            'followed_up_at' => now(),
        ]);

        DispositionHistory::create([
            'disposition_id' => $disposition->id,
            'user_id' => $dto->userId,
            'action' => 'Tindak Lanjut: ' . $dto->status,
            'notes' => $dto->followUpNotes,
        ]);

        return $disposition;
    }

    public function getPendingDispositionsForRecipient(int $userId): Collection
    {
        return Disposition::with(['letter', 'sender'])
            ->where('recipient_user_id', $userId)
            ->whereIn('status', ['Menunggu', 'Diproses'])
            ->orderBy('due_date', 'asc')
            ->get();
    }

    public function getSentDispositionsForSender(int $userId, int $limit = 5): Collection
    {
        return Disposition::with(['letter', 'recipient', 'recipientDepartment'])
            ->where('sender_user_id', $userId)
            ->latest()
            ->take($limit)
            ->get();
    }

    public function countPendingDispositionsForLetter(int $letterId): int
    {
        return Disposition::where('letter_id', $letterId)
            ->whereIn('status', ['Menunggu', 'Diproses'])
            ->count();
    }
}
