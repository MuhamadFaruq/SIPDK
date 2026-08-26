<?php

namespace App\Infrastructure\Repositories;

use App\Core\Contracts\Repositories\OutgoingLetterRepositoryInterface;
use App\Core\DTOs\OutgoingLetterDTO;
use App\Models\OutgoingLetter;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentOutgoingLetterRepository implements OutgoingLetterRepositoryInterface
{
    public function getPaginatedLetters(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = OutgoingLetter::with(['category', 'creator']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('agenda_number', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%")
                  ->orWhere('destination', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['degree'])) {
            $query->where('degree', $filters['degree']);
        }

        if (!empty($filters['date_start'])) {
            $query->whereDate('letter_date', '>=', $filters['date_start']);
        }

        if (!empty($filters['date_end'])) {
            $query->whereDate('letter_date', '<=', $filters['date_end']);
        }

        return $query->latest('letter_date')->paginate($perPage)->withQueryString();
    }

    public function findById(int $id): ?OutgoingLetter
    {
        return OutgoingLetter::with(['category', 'creator'])->find($id);
    }

    public function generateNextAgendaNumber(): string
    {
        $currentYear = date('Y');
        $lastLetter = OutgoingLetter::withTrashed()
            ->whereYear('created_at', $currentYear)
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastLetter && preg_match('/^(\d+)\/SK\//', $lastLetter->agenda_number, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        }

        return sprintf('%03d/SK/%s', $nextNumber, $currentYear);
    }

    public function createLetter(OutgoingLetterDTO $dto): OutgoingLetter
    {
        $agendaNumber = $dto->agendaNumber ?: $this->generateNextAgendaNumber();

        return OutgoingLetter::create([
            'agenda_number' => $agendaNumber,
            'reference_number' => $dto->referenceNumber,
            'letter_date' => $dto->letterDate,
            'destination' => $dto->destination,
            'subject' => $dto->subject,
            'summary' => $dto->summary,
            'category_id' => $dto->categoryId,
            'degree' => $dto->degree,
            'status' => $dto->status ?? 'Terkirim',
            'file_path' => $dto->filePath,
            'file_name' => $dto->fileName,
            'file_type' => $dto->fileType ?? 'pdf',
            'file_size' => $dto->fileSize ?? 0,
            'created_by' => $dto->createdBy,
        ]);
    }

    public function updateLetter(OutgoingLetter $letter, OutgoingLetterDTO $dto): OutgoingLetter
    {
        $data = [
            'reference_number' => $dto->referenceNumber,
            'letter_date' => $dto->letterDate,
            'destination' => $dto->destination,
            'subject' => $dto->subject,
            'summary' => $dto->summary,
            'category_id' => $dto->categoryId,
            'degree' => $dto->degree,
            'status' => $dto->status ?? $letter->status,
        ];

        if ($dto->filePath) {
            $data['file_path'] = $dto->filePath;
            $data['file_name'] = $dto->fileName;
            $data['file_type'] = $dto->fileType;
            $data['file_size'] = $dto->fileSize;
        }

        $letter->update($data);

        return $letter->fresh();
    }

    public function deleteLetter(OutgoingLetter $letter): bool
    {
        return $letter->delete();
    }

    public function getLatestLetters(int $limit = 6): Collection
    {
        return OutgoingLetter::with(['category', 'creator'])
            ->latest('letter_date')
            ->take($limit)
            ->get();
    }

    public function getCounts(): array
    {
        $today = Carbon::today();

        return [
            'total' => OutgoingLetter::count(),
            'today' => OutgoingLetter::whereDate('created_at', $today)->count(),
            'terkirim' => OutgoingLetter::where('status', 'Terkirim')->count(),
            'konsep' => OutgoingLetter::where('status', 'Konsep')->count(),
        ];
    }
}
