<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [
            ['loc' => route('home'),          'priority' => '1.0', 'freq' => 'weekly'],
            ['loc' => route('ebooks.index'),  'priority' => '0.9', 'freq' => 'daily'],
            ['loc' => route('about'),         'priority' => '0.5', 'freq' => 'monthly'],
            ['loc' => route('contact'),       'priority' => '0.4', 'freq' => 'monthly'],
        ];

        Ebook::where('status', 'published')
            ->select(['slug', 'updated_at'])
            ->orderByDesc('updated_at')
            ->get()
            ->each(function ($ebook) use (&$urls) {
                $urls[] = [
                    'loc'      => route('ebooks.show', $ebook->slug),
                    'lastmod'  => optional($ebook->updated_at)->toAtomString(),
                    'priority' => '0.8',
                    'freq'     => 'weekly',
                ];
            });

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1) . "</loc>\n";
            if (!empty($u['lastmod'])) {
                $xml .= '    <lastmod>' . $u['lastmod'] . "</lastmod>\n";
            }
            $xml .= '    <changefreq>' . $u['freq'] . "</changefreq>\n";
            $xml .= '    <priority>' . $u['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }
        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
