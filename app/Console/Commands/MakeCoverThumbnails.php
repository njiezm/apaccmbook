<?php

namespace App\Console\Commands;

use App\Models\Ebook;
use App\Support\CoverThumbnail;
use App\Support\OgImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Command;

class MakeCoverThumbnails extends Command
{
    protected $signature = 'covers:thumbs {--force : Régénère même si le fichier existe}';

    protected $description = 'Génère les miniatures WebP et les cartes de partage (Open Graph) des couvertures existantes';

    public function handle(): int
    {
        $ebooks = Ebook::whereNotNull('cover_image')->get();
        $done = 0;
        $skipped = 0;

        foreach ($ebooks as $ebook) {
            $thumb = CoverThumbnail::pathFor($ebook->cover_image);
            $og = OgImage::pathFor($ebook->cover_image);
            $disk = Storage::disk('public');

            $needThumb = $this->option('force') || !$disk->exists($thumb);
            $needOg = $this->option('force') || !$disk->exists($og);

            if (!$needThumb && !$needOg) {
                $skipped++;
                continue;
            }

            $ok = true;
            if ($needThumb) {
                $ok = (bool) CoverThumbnail::generate($ebook->cover_image) && $ok;
            }
            if ($needOg) {
                $ok = (bool) OgImage::generate($ebook->cover_image) && $ok;
            }

            if ($ok) {
                $done++;
                $this->line("✓ {$ebook->title}");
            } else {
                $this->warn("✗ Échec : {$ebook->title}");
            }
        }

        $this->info("Terminé. {$done} couverture(s) traitée(s), {$skipped} déjà à jour.");
        return self::SUCCESS;
    }
}
