<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;

class PendudukTemplatePdfExport
{
    protected $jumlahBaris;

    public function __construct($jumlahBaris = 50)
    {
        $this->jumlahBaris = $jumlahBaris;
    }

    /**
     * Download template PDF
     */
    public function download($filename = null)
    {
        if (!$filename) {
            $filename = 'template-penduduk-' . $this->jumlahBaris . '-baris-' . date('Y-m-d') . '.pdf';
        }
        
        $pdf = Pdf::loadView('exports.penduduk-template-pdf', [
            'jumlahBaris' => $this->jumlahBaris,
            'tanggal' => now()->format('d F Y'),
        ]);
        
        return $pdf->download($filename);
    }
}
