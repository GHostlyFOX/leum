<?php

namespace Modules\File\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\File\Models\File;

class FileService
{
    /**
     * Загрузить файл и создать запись в реестре.
     */
    public function upload(UploadedFile $file, string $directory = 'uploads'): File
    {
        $path = $file->store($directory, 's3');

        return File::create([
            'path'       => $path,
            'mime_type'  => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
        ]);
    }

    /**
     * Загрузить файл на публичный диск (для локальной разработки).
     */
    public function uploadPublic(UploadedFile $file, string $directory = 'uploads'): File
    {
        $path = $file->store($directory, 'public');

        return File::create([
            'path'       => $path,
            'mime_type'  => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
        ]);
    }

    /**
     * Получить файл по ID.
     */
    public function find(int $id): File
    {
        return File::findOrFail($id);
    }

    /**
     * Получить URL файла.
     */
    public function url(File $file, string $disk = 's3'): string
    {
        return Storage::disk($disk)->url($file->path);
    }

    /**
     * Удалить файл с диска и из реестра.
     */
    public function delete(File $file, string $disk = 's3'): void
    {
        Storage::disk($disk)->delete($file->path);
        $file->delete();
    }
}
