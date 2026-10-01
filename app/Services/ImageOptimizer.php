<?php

namespace App\Services;

/**
 * Lightweight image optimizer backed by GD (bundled with PHP).
 *
 * - Downscales images that exceed the given max dimension
 * - Re-encodes JPEG/WebP at a sane quality and PNG with compression
 * - Optionally converts alpha-free PNGs to JPEG (much smaller for photos)
 */
class ImageOptimizer
{
    /**
     * Optimize an image file in place. Keeps the same file name/format.
     * Returns true if the file was rewritten.
     */
    public static function optimizeInPlace(string $path, int $maxDim = 1200, int $quality = 82): bool
    {
        return self::optimize($path, $maxDim, $quality, false)['changed'];
    }

    /**
     * Optimize an image file.
     *
     * @return array{changed: bool, path: string, old_size: int, new_size: int, renamed: bool}
     *   'path' is the (possibly new) file path when a PNG was converted to JPEG.
     */
    public static function optimize(string $path, int $maxDim = 1200, int $quality = 82, bool $convertPng = true): array
    {
        $unchanged = ['changed' => false, 'path' => $path, 'old_size' => 0, 'new_size' => 0, 'renamed' => false];

        if (!is_file($path) || !extension_loaded('gd')) {
            return $unchanged;
        }

        $oldSize = filesize($path);
        $unchanged['old_size'] = $oldSize;

        $info = @getimagesize($path);
        if ($info === false) {
            return $unchanged;
        }

        [$width, $height] = $info;
        $mime = $info['mime'];

        if ($mime === 'image/gif') {
            return $unchanged; // GD would break animated GIFs
        }

        $src = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/webp' => @imagecreatefromwebp($path),
            default => null,
        };

        if (!$src) {
            return $unchanged;
        }

        // Palette images (PNG8 etc.) -> truecolor so alpha sampling works
        if (!imageistruecolor($src)) {
            imagepalettetotruecolor($src);
        }

        // Already small enough and within bounds — skip to keep runs idempotent
        $needsResize = max($width, $height) > $maxDim;
        if (!$needsResize && $oldSize < 150 * 1024 && $mime !== 'image/png') {
            imagedestroy($src);
            return $unchanged;
        }

        $hasAlpha = self::hasAlpha($src, $mime);

        // Downscale if needed
        if ($needsResize) {
            $scale = $maxDim / max($width, $height);
            $newW = max(1, (int) round($width * $scale));
            $newH = max(1, (int) round($height * $scale));

            $dst = imagecreatetruecolor($newW, $newH);
            if ($hasAlpha) {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
                imagefilledrectangle($dst, 0, 0, $newW, $newH, imagecolorallocatealpha($dst, 0, 0, 0, 127));
            } else {
                imagefilledrectangle($dst, 0, 0, $newW, $newH, imagecolorallocate($dst, 255, 255, 255));
            }
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $width, $height);
            imagedestroy($src);
            $src = $dst;
        }

        // PNG photo without transparency -> JPEG is dramatically smaller
        $targetPath = $path;
        $targetMime = $mime;
        if ($convertPng && $mime === 'image/png' && !$hasAlpha) {
            $targetPath = preg_replace('/\.png$/i', '.jpg', $path);
            $targetMime = 'image/jpeg';
        }

        if ($targetMime === 'image/jpeg') {
            // Flatten onto white + guarantee truecolor for JPEG output
            $flat = imagecreatetruecolor(imagesx($src), imagesy($src));
            imagefilledrectangle($flat, 0, 0, imagesx($src), imagesy($src), imagecolorallocate($flat, 255, 255, 255));
            imagecopy($flat, $src, 0, 0, 0, 0, imagesx($src), imagesy($src));
            imagedestroy($src);
            $src = $flat;
        }

        // Encode to memory first so we can bail out without touching the original
        ob_start();
        $ok = match ($targetMime) {
            'image/jpeg' => imagejpeg($src, null, $quality),
            'image/png' => imagepng($src, null, 6),
            'image/webp' => imagewebp($src, null, $quality),
            default => false,
        };
        $bytes = $ok ? ob_get_clean() : (ob_get_clean() && '');
        imagedestroy($src);

        if (!$ok || $bytes === false || $bytes === '') {
            return $unchanged;
        }

        $newSize = strlen($bytes);

        // Only rewrite when the saving is meaningful (keeps runs idempotent)
        if ($newSize >= (int) ($oldSize * 0.95)) {
            return $unchanged;
        }

        if (file_put_contents($targetPath, $bytes) === false) {
            return $unchanged;
        }

        if ($targetPath !== $path) {
            @unlink($path);
        }

        return [
            'changed' => true,
            'path' => $targetPath,
            'old_size' => $oldSize,
            'new_size' => $newSize,
            'renamed' => $targetPath !== $path,
        ];
    }

    private static function hasAlpha(\GdImage $img, string $mime): bool
    {
        if (!in_array($mime, ['image/png', 'image/webp'], true)) {
            return false;
        }

        // Sample a grid of pixels; alpha is in bits 24-30 for GD truecolor
        $w = imagesx($img);
        $h = imagesy($img);
        $steps = 40;
        for ($x = 0; $x < $steps; $x++) {
            for ($y = 0; $y < $steps; $y++) {
                $rgba = imagecolorat($img, (int) ($x * ($w - 1) / ($steps - 1)), (int) ($y * ($h - 1) / ($steps - 1)));
                if ((($rgba >> 24) & 0x7F) > 0) {
                    return true;
                }
            }
        }

        return false;
    }
}
