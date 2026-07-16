<?php

namespace App\Console\Commands;

use App\Models\Ebook;
use App\Support\CoverThumbnail;
use Illuminate\Console\Command;

class MakeCoverThumbnails extends Command
{
    protected $signature = 'covers:thumbs {--force : Régénère même si la miniature existe}';

    protected $description = 'Génère les miniatures WebP des couvertures d\'ebooks existantes';

    public function handle(): int
    {
        $ebooks = Ebook::whereNotNull('cover_image')->get();
        $done = 0;
        $skipped = 0;

        foreach ($ebooks as $ebook) {
            $thumb = CoverThumbnail::pathFor($ebook->cover_image);
            if (!$this->option('force') && \Illuminate\Support\Facades\Storage::disk('public')->exists($thumb)) {
                $skipped++;
                continue;
            }
            $result = CoverThumbnail::generate($ebook->cover_image);
            if ($result) {
                $done++;
                $this->line("✓ {$ebook->title}");
            } else {
                $this->warn("✗ Échec : {$ebook->title}");
            }
        }

        $this->info("Terminé. {$done} miniature(s) générée(s), {$skipped} déjà présente(s).");
        return self::SUCCESS;
    }
}
