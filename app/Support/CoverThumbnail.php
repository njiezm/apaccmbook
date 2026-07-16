<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class CoverThumbnail
{
    /** Chemin relatif (disk public) de la miniature d'une couverture. */
    public static function pathFor(string $coverPath): string
    {
        return 'covers/thumbs/' . pathinfo($coverPath, PATHINFO_FILENAME) . '.webp';
    }

    /**
     * Génère une miniature WebP (largeur max $width) à partir d'une couverture
     * stockée sur le disk public. Retourne le chemin de la miniature ou null.
     */
    public static function generate(?string $coverPath, int $width = 450): ?string
    {
        if (!$coverPath || !function_exists('imagewebp')) {
            return null;
        }

        $disk = Storage::disk('public');
        if (!$disk->exists($coverPath)) {
            return null;
        }

        $src = @imagecreatefromstring($disk->get($coverPath));
        if (!$src) {
            return null;
        }

        $w = imagesx($src);
        $h = imagesy($src);
        if ($w < 1 || $h < 1) {
            imagedestroy($src);
            return null;
        }

        $tw = min($width, $w);
        $th = (int) round($h * ($tw / $w));

        $dst = imagecreatetruecolor($tw, $th);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $tw, $th, $w, $h);

        $thumbPath = self::pathFor($coverPath);
        $tmp = tempnam(sys_get_temp_dir(), 'thumb');
        imagewebp($dst, $tmp, 80);
        $disk->put($thumbPath, file_get_contents($tmp));
        @unlink($tmp);

        imagedestroy($src);
        imagedestroy($dst);

        return $thumbPath;
    }

    /** Supprime la miniature associée à une couverture. */
    public static function delete(?string $coverPath): void
    {
        if (!$coverPath) {
            return;
        }
        Storage::disk('public')->delete(self::pathFor($coverPath));
    }
}
