<?php

namespace App\Libraries;

use CodeIgniter\HTTP\Files\UploadedFile;
use RuntimeException;

class SecureFileStorage
{
    private const ROOT = WRITEPATH . 'uploads-private';

    /**
     * @param array<string, string> $mimeByExtension
     * @return array{path: string, mime: string, size: int, original_name: string}
     */
    public static function store(UploadedFile $file, array $mimeByExtension, int $maxBytes, string $directory): array
    {
        if (! $file->isValid() || $file->hasMoved()) {
            throw new RuntimeException('File upload tidak valid.');
        }

        $extension = strtolower($file->getExtension());
        $mime = strtolower((string) $file->getMimeType());
        if ($file->getSize() > $maxBytes || ! isset($mimeByExtension[$extension]) || $mime !== strtolower($mimeByExtension[$extension])) {
            throw new RuntimeException('Format atau ukuran file tidak valid.');
        }

        $targetDirectory = self::ROOT . '/' . trim($directory, '/');
        if (! is_dir($targetDirectory) && ! mkdir($targetDirectory, 0700, true) && ! is_dir($targetDirectory)) {
            throw new RuntimeException('Storage file tidak dapat dibuat.');
        }

        $name = $file->getRandomName();
        $file->move($targetDirectory, $name);
        @chmod($targetDirectory . '/' . $name, 0600);

        return [
            'path' => 'private/' . trim($directory, '/') . '/' . $name,
            'mime' => $mime,
            'size' => (int) $file->getSize(),
            'original_name' => (string) $file->getClientName(),
        ];
    }

    public static function resolve(string $storedPath): ?string
    {
        $storedPath = ltrim($storedPath, '/');
        if (str_starts_with($storedPath, 'private/')) {
            $relative = substr($storedPath, strlen('private/'));
            if ($relative === '' || str_contains($relative, '..') || str_contains($relative, '\\')) {
                return null;
            }
            $path = self::ROOT . '/' . $relative;
        } elseif (str_starts_with($storedPath, 'uploads/')) {
            // Compatibility for existing records; all new sensitive uploads are private.
            $relative = substr($storedPath, strlen('uploads/'));
            if ($relative === '' || str_contains($relative, '..') || str_contains($relative, '\\')) {
                return null;
            }
            $path = FCPATH . 'uploads/' . $relative;
        } else {
            return null;
        }

        return is_file($path) ? $path : null;
    }

    public static function remove(?string $storedPath): void
    {
        if ($storedPath && ($path = self::resolve($storedPath)) !== null) {
            @unlink($path);
        }
    }
}
