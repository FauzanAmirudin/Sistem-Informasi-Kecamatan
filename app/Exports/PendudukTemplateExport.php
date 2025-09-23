<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Desa;

class PendudukTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    /** @var array<int,string> */
    private array $allColumns = [
        'nik',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'status_perkawinan',
        'pekerjaan',
        'pendidikan_terakhir',
        'alamat',
        'rt',
        'rw',
        'desa',
        'memiliki_ktp',
        'tanggal_rekam_ktp',
    ];

    /** @var array<int,string> */
    private array $selectedColumns;
    private int $numRows;

    public function __construct(?array $selectedColumns = null, int $numRows = 50)
    {
        $this->selectedColumns = $this->normalizeColumns($selectedColumns);
        $this->numRows = max(1, min(1000, $numRows));
    }

    private function normalizeColumns(?array $columns): array
    {
        if (!$columns || count($columns) === 0) {
            return $this->allColumns;
        }
        $columns = array_values(array_intersect($this->allColumns, $columns));
        return count($columns) ? $columns : $this->allColumns;
    }

    public function array(): array
    {
        $row = array_fill(0, count($this->selectedColumns), '');
        return array_fill(0, $this->numRows, $row);
    }

    public function headings(): array
    {
        return $this->selectedColumns;
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumnLetter = $this->columnLetter(count($this->selectedColumns));

        $sheet->getStyle('A1:' . $lastColumnLetter . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
        ]);

        $this->addNotes($sheet, $lastColumnLetter);

        return [1 => ['font' => ['bold' => true]]];
    }

    public function columnWidths(): array
    {
        $defaults = [20, 30, 15, 20, 15, 15, 20, 25, 25, 40, 5, 5, 20, 15, 20];
        $widths = [];
        for ($i = 0; $i < count($this->selectedColumns); $i++) {
            $letter = $this->columnLetter($i + 1);
            $widths[$letter] = $defaults[$i] ?? 15;
        }
        return $widths;
    }

    private function addNotes(Worksheet $sheet, string $lastColumnLetter): void
    {
        $desas = Desa::pluck('nama_desa')->toArray();

        // Example row beneath header
        $examples = [
            'A2' => 'Contoh: 3507082503990001',
            'B2' => 'Contoh: Budi Santoso',
            'C2' => 'Isi dengan: Laki-laki atau Perempuan',
            'D2' => 'Contoh: Malang',
            'E2' => 'Format: DD/MM/YYYY (contoh: 25/03/1999)',
            'F2' => 'Contoh: Islam, Kristen, Katolik, Hindu, Buddha, Konghucu',
            'G2' => 'Contoh: Belum Kawin, Kawin, Cerai Hidup, Cerai Mati',
            'H2' => 'Contoh: Petani, Guru, Wiraswasta, dll',
            'I2' => 'Contoh: SD, SMP, SMA, D3, S1, S2, S3',
            'J2' => 'Contoh: Jl. Mawar No. 10',
            'K2' => 'Contoh: 01',
            'L2' => 'Contoh: 05',
            'M2' => 'Isi dengan nama desa yang terdaftar',
            'N2' => 'Isi dengan: Ya atau Tidak',
            'O2' => 'Format: DD/MM/YYYY atau kosongkan jika tidak ada',
        ];

        // Only set examples for columns that exist
        $maxIndex = count($this->selectedColumns);
        $letters = [];
        for ($i = 1; $i <= $maxIndex; $i++) { $letters[] = $this->columnLetter($i); }
        foreach ($examples as $cell => $text) {
            $colLetter = preg_replace('/\d+/', '', $cell);
            if (in_array($colLetter, $letters, true)) {
                $sheet->setCellValue($cell, $text);
            }
        }
        $sheet->getStyle('A2:' . $lastColumnLetter . '2')->applyFromArray([
            'font' => ['italic' => true, 'color' => ['rgb' => '808080']],
        ]);

        // Notes section
        $sheet->setCellValue('A4', 'PETUNJUK PENGISIAN:');
        $sheet->setCellValue('A5', '1. Jangan mengubah format header (baris pertama)');
        $sheet->setCellValue('A6', '2. Isi data mulai dari baris ke-3');
        $sheet->setCellValue('A7', '3. Pastikan format tanggal adalah DD/MM/YYYY (contoh: 25/03/1999)');
        $sheet->setCellValue('A8', '4. Untuk jenis kelamin, isi dengan "Laki-laki" atau "Perempuan"');
        $sheet->setCellValue('A9', '5. Untuk memiliki KTP, isi dengan "Ya" atau "Tidak"');
        $sheet->setCellValue('A10', '6. Nama desa harus sesuai dengan yang terdaftar di sistem');

        $sheet->mergeCells('A4:' . $lastColumnLetter . '4');
        for ($r = 5; $r <= 10; $r++) {
            $sheet->mergeCells('A' . $r . ':' . $lastColumnLetter . $r);
        }

        $sheet->getStyle('A4')->applyFromArray(['font' => ['bold' => true]]);

        // List of desa
        $startRow = 12;
        $sheet->setCellValue('A' . $startRow, 'Daftar Desa yang Tersedia:');
        $sheet->getStyle('A' . $startRow)->applyFromArray(['font' => ['bold' => true]]);
        foreach ($desas as $index => $desa) {
            $sheet->setCellValue('A' . ($startRow + 1 + $index), $desa);
        }
    }

    private function columnLetter(int $index): string
    {
        // 1 -> A, 2 -> B ... 26 -> Z, 27 -> AA, etc.
        $letter = '';
        while ($index > 0) {
            $index--;
            $letter = chr(65 + ($index % 26)) . $letter;
            $index = intdiv($index, 26);
        }
        return $letter;
    }
}