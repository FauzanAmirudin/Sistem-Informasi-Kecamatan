<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AsetDesa extends Model
{
    use HasFactory;

    protected $fillable = [
        'desa_id',
        'kategori_aset',
        'nama_aset',
        'deskripsi',
        'nilai_perolehan',
        'nilai_sekarang',
        'tanggal_perolehan',
        'kondisi',
        'lokasi',
        'bukti_kepemilikan',
        'keterangan',
        'is_current',
        'updated_by',
        'update_reason',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_perolehan' => 'date',
            'nilai_perolehan' => 'decimal:2',
            'nilai_sekarang' => 'decimal:2',
            'is_current' => 'boolean',
        ];
    }

    // Relationships
    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatAsetDesa::class);
    }

    // Scopes
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori_aset', $kategori)->where('is_current', true);
    }

    // Helper methods
    public function createHistory($actionType, $changedBy, $reason = null)
    {
        if (!$this->exists) return;
        
        // Standarisasi action_type untuk memastikan konsistensi dengan enum di database
        $standardizedActionType = $actionType;
        if ($actionType === 'update') {
            $standardizedActionType = 'updated';
        } else if ($actionType === 'create') {
            $standardizedActionType = 'created';
        } else if ($actionType === 'delete') {
            $standardizedActionType = 'deleted';
        }

        $this->riwayat()->create([
            'desa_id' => $this->desa_id,
            'kategori_aset' => $this->kategori_aset,
            'nama_aset' => $this->nama_aset,
            'deskripsi' => $this->deskripsi,
            'nilai_perolehan' => $this->nilai_perolehan,
            'nilai_sekarang' => $this->nilai_sekarang,
            'tanggal_perolehan' => $this->tanggal_perolehan,
            'kondisi' => $this->kondisi,
            'lokasi' => $this->lokasi,
            'bukti_kepemilikan' => $this->bukti_kepemilikan,
            'keterangan' => $this->keterangan,
            'action_type' => $standardizedActionType,
            'changed_by' => $changedBy,
            'change_reason' => $reason,
        ]);
    }

    public function getTotalNilaiAttribute()
    {
        return $this->nilai_sekarang ?? $this->nilai_perolehan ?? 0;
    }

    public function getFileUrlAttribute()
    {
        return $this->bukti_kepemilikan ? asset('uploads/' . $this->bukti_kepemilikan) : null;
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($aset) {
            if (Auth::check()) {
                $aset->createHistory('created', Auth::id(), 'Data aset desa baru ditambahkan');
            }
        });

        static::updating(function ($aset) {
            if ($aset->isDirty() && !$aset->isDirty('updated_by') && Auth::check()) {
                $aset->createHistory('updated', Auth::id(), $aset->update_reason);
            }
        });

        static::deleting(function ($aset) {
            if (Auth::check()) {
                $aset->createHistory('deleted', Auth::id(), 'Data aset desa dihapus');
            }
        });
    }
}