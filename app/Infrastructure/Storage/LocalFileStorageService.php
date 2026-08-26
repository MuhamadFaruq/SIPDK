<?php

namespace App\Infrastructure\Storage;

use App\Core\Contracts\Services\FileStorageServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

class LocalFileStorageService implements FileStorageServiceInterface
{
    private const ALLOWED_EXTENSIONS = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];

    public function storeLetterFile(UploadedFile $file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            throw new InvalidArgumentException("Format berkas .{$extension} tidak diizinkan untuk keamanan sistem.");
        }

        // Sanitize original file name (strip null bytes, path traversal, dangerous characters)
        $cleanOriginalName = preg_replace('/[^\w\s\.-]/i', '', basename(str_replace(["\0", '../', '..\\'], '', $file->getClientOriginalName())));
        if (empty($cleanOriginalName)) {
            $cleanOriginalName = 'dokumen_' . time() . '.' . $extension;
        }

        // Cryptographically secure randomized storage filename to prevent direct path traversal or overwrite attacks
        $randomStorageName = Str::random(40) . '.' . $extension;
        $filePath = $file->storeAs('letters', $randomStorageName, 'public');

        return [
            'file_path' => $filePath,
            'file_name' => $cleanOriginalName,
            'file_type' => $extension,
            'file_size' => $file->getSize(),
        ];
    }

    public function deleteFile(?string $path): bool
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }
}
