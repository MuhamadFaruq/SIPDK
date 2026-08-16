<?php

namespace App\UseCases\Report;

use App\Core\Contracts\Repositories\LetterRepositoryInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportReportCsvUseCase
{
    public function __construct(
        private readonly LetterRepositoryInterface $letterRepository,
    ) {}

    public function execute(string $startDate, string $endDate): StreamedResponse
    {
        $letters = $this->letterRepository->getLettersForReport($startDate, $endDate);
        $filename = "Laporan_Persuratan_SIPDK_{$startDate}_to_{$endDate}.csv";

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($letters) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'No. Agenda',
                'No. Surat',
                'Tgl Surat',
                'Tgl Terima',
                'Pengirim',
                'Perihal',
                'Kategori',
                'Sifat',
                'Status',
                'Petugas Agenda'
            ]);

            foreach ($letters as $l) {
                fputcsv($file, [
                    $l->agenda_number,
                    $l->reference_number,
                    $l->letter_date->format('d/m/Y'),
                    $l->received_date->format('d/m/Y'),
                    $l->sender,
                    $l->subject,
                    $l->category->name ?? '-',
                    $l->degree,
                    $l->status,
                    $l->creator->name ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
