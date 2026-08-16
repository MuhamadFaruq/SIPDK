<?php

namespace App\Core\Contracts\Services;

use Illuminate\Http\UploadedFile;

interface FileStorageServiceInterface
{
    public function storeLetterFile(UploadedFile $file): array;

    public function deleteFile(?string $path): bool;
}
