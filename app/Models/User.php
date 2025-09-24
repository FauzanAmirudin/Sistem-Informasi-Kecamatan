<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'desa_id',
        'phone',
        'address',
        'profile_photo',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function dokumens()
    {
        return $this->hasMany(Dokumen::class, 'uploaded_by');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    // Accessor for profile photo URL
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo) {
            return Storage::disk('uploads')->url('profile-photos/' . $this->profile_photo);
        }
        return asset('images/default-avatar.svg');
    }

    // Helper methods
    public function isAdminKecamatan()
    {
        return $this->role === 'admin_kecamatan';
    }

    public function isAdminDesa()
    {
        return $this->role === 'admin_desa';
    }

    // Activity logging method
    public function recordActivity($event, $description, $properties = [])
    {
        $this->activities()->create([
            'log_name' => $event,
            'description' => $description,
            'subject_type' => get_class($this),
            'subject_id' => $this->id,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}