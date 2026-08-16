<?php

namespace App\Infrastructure\Repositories;

use App\Core\Contracts\Repositories\LetterRepositoryInterface;
use App\Core\DTOs\LetterDTO;
use App\Models\Letter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentLetterRepository implements LetterRepositoryInterface
{
    public function getPaginatedLetters(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Letter::with(['category', 'creator', 'dispositions.recipient']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('agenda_number', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%")
                  ->orWhere('sender', 'like', "%{$search}%")
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

        if (!empty($filters['year'])) {
            $query->whereYear('received_date', $filters['year']);
        }

        if (!empty($filters['month'])) {
            $query->whereMonth('received_date', $filters['month']);
        }

        if (!empty($filters['date_start'])) {
            $query->whereDate('received_date', '>=', $filters['date_start']);
        }

        if (!empty($filters['date_end'])) {
            $query->whereDate('received_date', '<=', $filters['date_end']);
        }

        return $query->latest('received_date')->paginate($perPage)->withQueryString();
    }

    public function findById(int $id): ?Letter
    {
        return Letter::with(['category', 'creator', 'dispositions.sender', 'dispositions.recipient', 'dispositions.histories.user'])->find($id);
    }

    public function generateNextAgendaNumber(): string
    {
        $prefix = 'AGD-' . date('Y/m/');
        $lastLetter = Letter::where('agenda_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastLetter) {
            $lastNum = (int) substr($lastLetter->agenda_number, -3);
            $nextNum = sprintf('%03d', $lastNum + 1);
        } else {
            $nextNum = '001';
        }

        return $prefix . $nextNum;
    }

    public function createLetter(LetterDTO $dto): Letter
    {
        $agendaNumber = $dto->agendaNumber ?? $this->generateNextAgendaNumber();

        return Letter::create([
            'agenda_number' => $agendaNumber,
            'reference_number' => $dto->referenceNumber,
            'letter_date' => $dto->letterDate,
            'received_date' => $dto->receivedDate,
            'sender' => $dto->sender,
            'subject' => $dto->subject,
            'summary' => $dto->summary,
            'category_id' => $dto->categoryId,
            'degree' => $dto->degree,
            'file_path' => $dto->filePath,
            'file_name' => $dto->fileName,
            'file_type' => $dto->fileType,
            'file_size' => $dto->fileSize ?? 0,
            'status' => $dto->status ?? 'Baru',
            'created_by' => $dto->createdBy,
        ]);
    }

    public function updateLetter(Letter $letter, LetterDTO $dto): Letter
    {
        $updateData = [
            'reference_number' => $dto->referenceNumber,
            'letter_date' => $dto->letterDate,
            'received_date' => $dto->receivedDate,
            'sender' => $dto->sender,
            'subject' => $dto->subject,
            'summary' => $dto->summary,
            'category_id' => $dto->categoryId,
            'degree' => $dto->degree,
            'status' => $dto->status ?? $letter->status,
        ];

        if ($dto->filePath) {
            $updateData['file_path'] = $dto->filePath;
            $updateData['file_name'] = $dto->fileName;
            $updateData['file_type'] = $dto->fileType;
            $updateData['file_size'] = $dto->fileSize;
        }

        $letter->update($updateData);
        return $letter;
    }

    public function deleteLetter(Letter $letter): bool
    {
        return $letter->delete();
    }

    public function getLatestLetters(int $limit = 6): Collection
    {
        return Letter::with('category', 'creator')
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getLettersForReport(string $startDate, string $endDate, ?int $categoryId = null, ?string $status = null): Collection
    {
        $query = Letter::with(['category', 'creator', 'dispositions.recipient', 'dispositions.recipientDepartment'])
            ->whereDate('received_date', '>=', $startDate)
            ->whereDate('received_date', '<=', $endDate);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('received_date', 'asc')->get();
    }

    public function getCounts(): array
    {
        return [
            'total' => Letter::count(),
            'today' => Letter::whereDate('created_at', today())->count(),
            'baru' => Letter::where('status', 'Baru')->count(),
            'diproses' => Letter::whereIn('status', ['Didisposisi', 'Diproses'])->count(),
            'selesai' => Letter::where('status', 'Selesai')->count(),
        ];
    }

    public function getDistinctYears(): Collection
    {
        $years = Letter::pluck('received_date')
            ->filter()
            ->map(fn($date) => $date->format('Y'))
            ->unique()
            ->sortDesc()
            ->values();

        if ($years->isEmpty()) {
            return collect([date('Y')]);
        }

        return $years;
    }
}
