<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AsetTanahWarga extends Model
{
    use HasFactory;

    protected $fillable = [
        'desa_id',
        'nama_pemilik',
        'nik_pemilik',
        'nomor_sph',
        'tanggal_sph',
        'luas_tanah',
        'nilai_per_meter',
        'lokasi',
        'jenis_tanah',
        'status_kepemilikan',
        'tanggal_perolehan',
        'bukti_kepemilikan',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_perolehan' => 'date',
            'tanggal_sph' => 'date',
            'luas_tanah' => 'decimal:2',
            'nilai_per_meter' => 'decimal:0',
        ];
    }

    // Relationships
    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    // Helper methods
    public function getNilaiTanahAttribute()
    {
        if ($this->luas_tanah > 0 && $this->nilai_per_meter > 0) {
            return $this->luas_tanah * $this->nilai_per_meter;
        }
        return 0;
    }

    public function getFileUrlAttribute()
    {
        return $this->bukti_kepemilikan ? asset('uploads/' . $this->bukti_kepemilikan) : null;
    }
}