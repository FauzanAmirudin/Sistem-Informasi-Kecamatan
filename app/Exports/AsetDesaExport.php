<?php

namespace App\Exports;

use App\Models\AsetDesa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AsetDesaExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = AsetDesa::with('desa')->current();

        if (!empty($this->filters['desa_id'])) {
            $query->where('desa_id', $this->filters['desa_id']);
        }

        if (!empty($this->filters['kategori_aset'])) {
            $query->where('kategori_aset', $this->filters['kategori_aset']);
        }

        if (!empty($this->filters['kondisi'])) {
            $query->where('kondisi', $this->filters['kondisi']);
        }

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nama_aset', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('nama_aset')->get();
    }

    public function headings(): array
    {
        return [
            'No',
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

    public function map($asetDesa): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $asetDesa->desa->nama_desa,
            ucfirst($asetDesa->kategori_aset),
            $asetDesa->nama_aset,
            $asetDesa->deskripsi,
            $asetDesa->nilai_perolehan ? 'Rp ' . number_format($asetDesa->nilai_perolehan, 0, ',', '.') : '-',
            $asetDesa->nilai_sekarang ? 'Rp ' . number_format($asetDesa->nilai_sekarang, 0, ',', '.') : '-',
            $asetDesa->tanggal_perolehan->format('d/m/Y'),
            ucfirst(str_replace('_', ' ', $asetDesa->kondisi)),
            $asetDesa->lokasi,
            $asetDesa->keterangan,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
