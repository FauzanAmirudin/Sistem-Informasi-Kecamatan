<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Traits\LogsActivity;

class PerangkatDesa extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'desa_id',
        'nama_lengkap',
        'jabatan',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'pendidikan_terakhir',
        'alamat',
        'no_telepon',
        'tanggal_mulai_tugas',
        'tanggal_akhir_tugas',
        'sk_pengangkatan',
        'jobdesk',
        'status',
        'is_current',
        'updated_by',
        'update_reason',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'tanggal_mulai_tugas' => 'date',
            'tanggal_akhir_tugas' => 'date',
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
        return $this->hasMany(RiwayatPerangkatDesa::class);
    }

    // Scopes
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif')->where('is_current', true);
    }

    public function getFileUrlAttribute()
    {
        return $this->sk_pengangkatan ? asset('uploads/' . $this->sk_pengangkatan) : null;
    }

    // Helper methods
    public function createHistory($actionType, $changedBy, $reason = null)
    {
        if (!$this->exists) return;

        $changes = [];
        $dirty = $this->getDirty();
        
        foreach ($dirty as $field => $newValue) {
            if ($field !== 'updated_at' && $field !== 'update_reason') {
                $changes[$field] = [
                    'old' => $this->getOriginal($field),
                    'new' => $newValue
                ];
            }
        }

        $this->riwayat()->create([
            'desa_id' => $this->desa_id,
            'nama_lengkap' => $this->nama_lengkap,
            'jabatan' => $this->jabatan,
            'nik' => $this->nik,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jenis_kelamin' => $this->jenis_kelamin,
            'pendidikan_terakhir' => $this->pendidikan_terakhir,
            'alamat' => $this->alamat,
            'no_telepon' => $this->no_telepon,
            'tanggal_mulai_tugas' => $this->tanggal_mulai_tugas,
            'tanggal_akhir_tugas' => $this->tanggal_akhir_tugas,
            'sk_pengangkatan' => $this->sk_pengangkatan,
            'jobdesk' => $this->jobdesk,
            'status' => $this->status,
            'action_type' => $actionType,
            'changed_by' => $changedBy,
            'change_reason' => $reason,
            'perubahan' => json_encode($changes),
        ]);
    }

    protected static function boot()
    {
        parent::boot();
        static::bootLogsActivity();

        static::created(function ($perangkat) {
            if (Auth::check()) {
                $perangkat->createHistory('created', Auth::id(), 'Data perangkat desa baru ditambahkan');
            }
        });

        static::updating(function ($perangkat) {
            if ($perangkat->isDirty() && !$perangkat->isDirty('updated_by') && Auth::check()) {
                $perangkat->createHistory('updated', Auth::id(), $perangkat->update_reason);
            }
        });

        static::deleting(function ($perangkat) {
            if (Auth::check()) {
                $perangkat->createHistory('deleted', Auth::id(), 'Data perangkat desa dihapus');
            }
        });
    }
}