<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscriberController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:manage-ebooks']);
    }

    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();

        return back()->with('status', 'Abonné supprimé.');
    }

    /**
     * Export CSV des abonnés confirmés (compatible Excel FR : séparateur « ; » + BOM UTF-8).
     */
    public function export(): StreamedResponse
    {
        $filename = 'abonnes-newsletter-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            // BOM UTF-8 pour qu'Excel affiche correctement les accents
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Email', 'Statut', 'Inscrit le'], ';');

            Subscriber::orderBy('created_at')
                ->chunk(500, function ($chunk) use ($out) {
                    foreach ($chunk as $s) {
                        fputcsv($out, [
                            $s->email,
                            $s->is_active ? 'Confirmé' : 'En attente',
                            optional($s->created_at)->format('d/m/Y H:i'),
                        ], ';');
                    }
                });

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
