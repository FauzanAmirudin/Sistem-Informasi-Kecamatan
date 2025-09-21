<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Desa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_desa',
        'kode_desa',
        'kepala_desa',
        'sk_kepala_desa',
        'alamat',
        'kode_pos',
        'latitude',
        'longitude',
        'luas_wilayah',
        'komoditas_unggulan',
        'kondisi_sosial_ekonomi',
        'monografi_file',
        'status',
        'last_updated_at',
    ];

    protected function casts(): array
    {
        return [
            'last_updated_at' => 'datetime',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'luas_wilayah' => 'decimal:2',
        ];
    }

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function penduduks()
    {
        return $this->hasMany(Penduduk::class);
    }

    public function perangkatDesas()
    {
        return $this->hasMany(PerangkatDesa::class);
    }

    public function asetDesas()
    {
        return $this->hasMany(AsetDesa::class);
    }

    public function asetTanahWargas()
    {
        return $this->hasMany(AsetTanahWarga::class);
    }

    public function dokumens()
    {
        return $this->hasMany(Dokumen::class);
    }
    
    public function activities()
    {
        return $this->hasMany(Activity::class, 'desa_id');
    }

    // Helper methods
    public function getTotalPendudukAttribute()
    {
        return $this->penduduks()->count();
    }

    public function getTotalPendudukPriaAttribute()
    {
        return $this->penduduks()->where('jenis_kelamin', 'L')->count();
    }

    public function getTotalPendudukWanitaAttribute()
    {
        return $this->penduduks()->where('jenis_kelamin', 'P')->count();
    }

    public function getTotalPerangkatAttribute()
    {
        return $this->perangkatDesas()->where('status', 'aktif')->count();
    }

    public function getStatusUpdateAttribute()
    {
        if (!$this->last_updated_at) {
            return 'merah';
        }

        $daysSinceUpdate = abs(Carbon::now()->diffInDays($this->last_updated_at));
        
        if ($daysSinceUpdate <= 7) {
            return 'hijau';
        } elseif ($daysSinceUpdate <= 30) {
            return 'kuning';
        } else {
            return 'merah';
        }
    }

    public function updateLastUpdated()
    {
        $this->update(['last_updated_at' => Carbon::now()]);
    }
}