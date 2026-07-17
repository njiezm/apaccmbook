<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Génère une carte de partage social (Open Graph) 1200×630 au format JPEG
 * à partir de la couverture d'un ebook.
 *
 * Pourquoi une image dédiée plutôt que la couverture brute ?
 *  - WhatsApp / Facebook / Twitter attendent un ratio paysage 1.91:1 (1200×630).
 *    Une couverture portrait est affichée en minuscule vignette, voire ignorée.
 *  - Le JPEG est universellement supporté par les robots d'aperçu (le WebP ne l'est pas).
 *  - On maîtrise le poids (< 300 Ko) pour que WhatsApp affiche le GRAND aperçu.
 *
 * Rendu : la couverture nette est centrée sur un fond flouté et assombri
 * généré depuis la couverture elle-même (aucune police externe requise).
 */
class OgImage
{
    public const W = 1200;
    public const H = 630;

    /** Chemin relatif (disk public) de la carte de partage d'une couverture. */
    public static function pathFor(string $coverPath): string
    {
        return 'covers/og/' . pathinfo($coverPath, PATHINFO_FILENAME) . '.jpg';
    }

    /**
     * Génère la carte 1200×630. Retourne le chemin relatif ou null en cas d'échec.
     */
    public static function generate(?string $coverPath): ?string
    {
        if (!$coverPath || !function_exists('imagejpeg') || !function_exists('imagecreatefromstring')) {
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

        $sw = imagesx($src);
        $sh = imagesy($src);
        if ($sw < 1 || $sh < 1) {
            imagedestroy($src);
            return null;
        }

        $canvas = imagecreatetruecolor(self::W, self::H);

        // ── 1. Fond : couverture recadrée « cover-fit », floutée puis assombrie ──
        self::drawBlurredBackground($canvas, $src, $sw, $sh);

        // ── 2. Premier plan : couverture nette centrée avec une ombre portée ──
        self::drawCover($canvas, $src, $sw, $sh);

        // ── 3. Bande d'accent cardinal en bas (identité APACC-M) ──
        $cardinal = imagecolorallocate($canvas, 0xB9, 0x1C, 0x1C);
        imagefilledrectangle($canvas, 0, self::H - 8, self::W, self::H, $cardinal);

        $thumbPath = self::pathFor($coverPath);
        $tmp = tempnam(sys_get_temp_dir(), 'og');
        imagejpeg($canvas, $tmp, 82);
        $disk->put($thumbPath, file_get_contents($tmp));
        @unlink($tmp);

        imagedestroy($src);
        imagedestroy($canvas);

        return $thumbPath;
    }

    /** Supprime la carte de partage associée à une couverture. */
    public static function delete(?string $coverPath): void
    {
        if (!$coverPath) {
            return;
        }
        Storage::disk('public')->delete(self::pathFor($coverPath));
    }

    /** Fond flouté « cover-fit » (recadré pour remplir tout le canevas). */
    private static function drawBlurredBackground($canvas, $src, int $sw, int $sh): void
    {
        $dstRatio = self::W / self::H;
        $srcRatio = $sw / $sh;

        if ($srcRatio > $dstRatio) {
            $cropH = $sh;
            $cropW = (int) round($sh * $dstRatio);
            $cropX = (int) (($sw - $cropW) / 2);
            $cropY = 0;
        } else {
            $cropW = $sw;
            $cropH = (int) round($sw / $dstRatio);
            $cropX = 0;
            $cropY = (int) (($sh - $cropH) / 2);
        }

        // Astuce perf : on floute à taille réduite puis on agrandit (flou lisse & bon marché).
        $bw = 200; $bh = 105;
        $small = imagecreatetruecolor($bw, $bh);
        imagecopyresampled($small, $src, 0, 0, $cropX, $cropY, $bw, $bh, $cropW, $cropH);
        if (function_exists('imagefilter')) {
            for ($i = 0; $i < 6; $i++) {
                @imagefilter($small, IMG_FILTER_GAUSSIAN_BLUR);
            }
        }
        imagecopyresampled($canvas, $small, 0, 0, 0, 0, self::W, self::H, $bw, $bh);
        imagedestroy($small);

        // Voile sombre pour faire ressortir la couverture nette.
        $overlay = imagecreatetruecolor(self::W, self::H);
        imagefilledrectangle($overlay, 0, 0, self::W, self::H, imagecolorallocate($overlay, 20, 18, 15));
        imagecopymerge($canvas, $overlay, 0, 0, 0, 0, self::W, self::H, 55);
        imagedestroy($overlay);
    }

    /** Couverture nette, centrée, avec une ombre portée douce. */
    private static function drawCover($canvas, $src, int $sw, int $sh): void
    {
        $maxH = 500;
        $maxW = 380;
        $scale = min($maxW / $sw, $maxH / $sh);
        $fw = (int) round($sw * $scale);
        $fh = (int) round($sh * $scale);
        $fx = (int) round((self::W - $fw) / 2);
        $fy = (int) round((self::H - $fh) / 2);

        // Ombre portée : plusieurs rectangles semi-transparents décalés.
        for ($o = 10; $o >= 2; $o -= 2) {
            $shadow = imagecreatetruecolor($fw + $o * 2, $fh + $o * 2);
            imagefilledrectangle($shadow, 0, 0, $fw + $o * 2, $fh + $o * 2, imagecolorallocate($shadow, 0, 0, 0));
            imagecopymerge($canvas, $shadow, $fx - $o, $fy - $o + 6, 0, 0, $fw + $o * 2, $fh + $o * 2, 8);
            imagedestroy($shadow);
        }

        $cover = imagecreatetruecolor($fw, $fh);
        imagecopyresampled($cover, $src, 0, 0, 0, 0, $fw, $fh, $sw, $sh);
        imagecopy($canvas, $cover, $fx, $fy, 0, 0, $fw, $fh);
        imagedestroy($cover);
    }
}
