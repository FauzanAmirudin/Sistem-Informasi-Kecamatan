<?php

namespace App\Imports;

use App\Models\AsetDesa;
use App\Models\Desa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AsetDesaImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    public function model(array $row)
    {
        // Cari desa berdasarkan nama
        $desa = Desa::where('nama_desa', $row['desa'])->first();
        
        if (!$desa) {
            throw new \Exception("Desa '{$row['desa']}' tidak ditemukan");
        }

        // Convert kategori aset
        $kategoriAset = strtolower($row['kategori_aset']);
        if (!in_array($kategoriAset, ['tanah', 'bangunan', 'inventaris'])) {
            throw new \Exception("Kategori aset '{$row['kategori_aset']}' tidak valid. Gunakan: tanah, bangunan, atau inventaris");
        }

        // Convert kondisi
        $kondisi = strtolower(str_replace(' ', '_', $row['kondisi']));
        if (!in_array($kondisi, ['baik', 'rusak_ringan', 'rusak_berat'])) {
            throw new \Exception("Kondisi '{$row['kondisi']}' tidak valid. Gunakan: baik, rusak ringan, atau rusak berat");
        }

        // Parse tanggal
        $tanggalPerolehan = null;
        if (!empty($row['tanggal_perolehan'])) {
            try {
                $tanggalPerolehan = Carbon::createFromFormat('d/m/Y', $row['tanggal_perolehan']);
            } catch (\Exception $e) {
                try {
                    $tanggalPerolehan = Carbon::createFromFormat('Y-m-d', $row['tanggal_perolehan']);
                } catch (\Exception $e) {
                    throw new \Exception("Format tanggal '{$row['tanggal_perolehan']}' tidak valid. Gunakan format dd/mm/yyyy atau yyyy-mm-dd");
                }
            }
        }

        // Parse nilai
        $nilaiPerolehan = null;
        if (!empty($row['nilai_perolehan'])) {
            $nilaiPerolehan = $this->parseNilai($row['nilai_perolehan']);
        }

        $nilaiSekarang = null;
        if (!empty($row['nilai_sekarang'])) {
            $nilaiSekarang = $this->parseNilai($row['nilai_sekarang']);
        }

        return new AsetDesa([
            'desa_id' => $desa->id,
            'kategori_aset' => $kategoriAset,
            'nama_aset' => $row['nama_aset'],
            'deskripsi' => $row['deskripsi'] ?? null,
            'nilai_perolehan' => $nilaiPerolehan,
            'nilai_sekarang' => $nilaiSekarang,
            'tanggal_perolehan' => $tanggalPerolehan,
            'kondisi' => $kondisi,
            'lokasi' => $row['lokasi'],
            'keterangan' => $row['keterangan'] ?? null,
            'is_current' => true,
            'updated_by' => Auth::id(),
        ]);
    }

    private function parseNilai($nilai)
    {
        // Remove currency symbols and spaces
        $nilai = preg_replace('/[Rp\s,.]/', '', $nilai);
        
        // Convert to float
        return (float) $nilai;
    }

    public function rules(): array
    {
        return [
            'desa' => 'required|string',
            'kategori_aset' => 'required|string',
            'nama_aset' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'nilai_perolehan' => 'nullable|numeric|min:0',
            'nilai_sekarang' => 'nullable|numeric|min:0',
            'tanggal_perolehan' => 'required|string',
            'kondisi' => 'required|string',
            'lokasi' => 'required|string',
            'keterangan' => 'nullable|string',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
