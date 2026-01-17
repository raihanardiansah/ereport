<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageCompressionService
{
    /**
     * Compress and save avatar image.
     * Resizes to max 500x500 and compresses to JPEG quality 80.
     */
    public function compressAndSaveAvatar(UploadedFile $file, string $directory = 'avatars'): string
    {
        return $this->compressAndSaveImage($file, $directory, 500, 500, 80);
    }

    /**
     * Compress and save generic image.
     * Resizes to max specified dimensions and compresses to JPEG.
     */
    public function compressAndSaveImage(UploadedFile $file, string $directory, int $maxWidth = 1200, int $maxHeight = 1200, int $quality = 80): string
    {
        // Get image info
        $imageInfo = getimagesize($file->getPathname());
        $mime = $imageInfo['mime'];
        $width = $imageInfo[0];
        $height = $imageInfo[1];

        // Create new image from file
        try {
            $sourceImage = match ($mime) {
                'image/jpeg', 'image/jpg' => imagecreatefromjpeg($file->getPathname()),
                'image/png' => imagecreatefrompng($file->getPathname()),
                'image/webp' => imagecreatefromwebp($file->getPathname()),
                default => throw new \Exception('Unsupported image format'),
            };
        } catch (\Throwable $e) {
            // Fallback for corrupt images or unsupported types checking
            throw new \Exception('Gagal memproses gambar: ' . $e->getMessage());
        }

        // Resize if larger than max dimensions
        if ($width > $maxWidth || $height > $maxHeight) {
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = (int) ($width * $ratio);
            $newHeight = (int) ($height * $ratio);

            $newImage = imagecreatetruecolor($newWidth, $newHeight);

            // Preserve transparency for PNG/WebP (handling before flatten)
            if ($mime === 'image/png' || $mime === 'image/webp') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
            }

            imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($sourceImage);
            $sourceImage = $newImage;
        }

        // Generate filename
        $filename = Str::uuid() . '.jpg';
        $path = $directory . '/' . $filename;
        
        // Save as JPEG to temporary path
        $tempPath = sys_get_temp_dir() . '/' . $filename;
        
        // Ensure white background for transparent images when converting to JPEG
        if ($mime === 'image/png' || $mime === 'image/webp') {
            $bg = imagecreatetruecolor(imagesx($sourceImage), imagesy($sourceImage));
            $white = imagecolorallocate($bg, 255, 255, 255);
            imagefill($bg, 0, 0, $white);
            imagecopy($bg, $sourceImage, 0, 0, 0, 0, imagesx($sourceImage), imagesy($sourceImage));
            imagedestroy($sourceImage);
            $sourceImage = $bg;
        }

        imagejpeg($sourceImage, $tempPath, $quality);
        imagedestroy($sourceImage);

        // Store to storage using Laravel Storage
        Storage::disk('public')->putFileAs($directory, new \Illuminate\Http\File($tempPath), $filename);

        // Clean up temp file
        @unlink($tempPath);

        return $path;
    }
}
