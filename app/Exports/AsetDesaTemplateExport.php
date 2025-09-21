<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AsetDesaTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            // Template data - kosong, hanya untuk download template
        ];
    }

    public function headings(): array
    {
        return [
            'Desa',
            'Kategori Aset',
            'Nama Aset',
            'Deskripsi',
            'Nilai Perolehan',
            'Nilai Sekarang',
            'Tanggal Perolehan',
            'Kondisi',
            'Lokasi',
            'Keterangan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
