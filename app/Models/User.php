<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'satuan_kerja_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // =============================
    // Relasi
    // =============================

    public function satuanKerja()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja_id');
    }

    public function pelaporans()
    {
        return $this->hasMany(Pelaporan::class, 'pptk_id');
    }

    public function verifikasiLaporans()
    {
        return $this->hasMany(VerifikasiLaporan::class, 'verifikator_id');
    }

    public function berkas()
    {
        return $this->hasMany(Berkas::class, 'uploaded_by');
    }
}
