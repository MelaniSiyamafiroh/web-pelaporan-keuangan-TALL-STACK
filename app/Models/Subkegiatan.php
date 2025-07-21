<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subkegiatan extends Model
{
    use HasFactory;

    protected $table = 'subkegiatans';

    protected $fillable = [
        'kegiatan_id',
        'nama_subkegiatan',
        'tahun_anggaran',
        'rekening',
        'jumlah_pagu',
    ];

    // =============================
    // Relasi
    // =============================

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function pelaporans()
    {
        return $this->hasMany(Pelaporan::class, 'subkegiatan_id');
    }

    // =============================
    // Scope
    // =============================

    public function scopeTahunAktif($query)
    {
        return $query->where('tahun_anggaran', session('tahun_aktif'));
    }
}
