<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatans';

    protected $fillable = [
        'nama_kegiatan',
        'tahun',
        'satuan_kerja_id',
    ];

    // =============================
    // Relasi
    // =============================

    public function satuanKerja()
    {
        return $this->belongsTo(SatuanKerja::class, 'satuan_kerja_id');
    }

    public function subkegiatans()
    {
        return $this->hasMany(Subkegiatan::class, 'kegiatan_id');
    }

    public function pelaporans()
    {
        return $this->hasMany(Pelaporan::class, 'kegiatan_id');
    }

    // =============================
    // Scope
    // =============================

    public function scopeTahunAktif($query)
    {
        return $query->where('tahun', session('tahun_aktif'));
    }
}
