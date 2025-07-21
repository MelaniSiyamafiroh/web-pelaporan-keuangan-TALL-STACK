<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanTahunan extends Model
{
    use HasFactory;

    protected $table = 'laporan_tahunans';

    protected $fillable = [
        'tahun',
        'satuan_kerja_id',
        'catatan',
        'file_path',
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
        return $this->hasMany(Pelaporan::class, 'laporan_tahunan_id');
    }

    // =============================
    // Scope
    // =============================

    public function scopeTahunAktif($query)
    {
        return $query->where('tahun', session('tahun_aktif'));
    }
}
