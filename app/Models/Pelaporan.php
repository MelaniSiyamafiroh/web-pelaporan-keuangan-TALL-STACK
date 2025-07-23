<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelaporan extends Model
{
    use HasFactory;

    protected $table = 'pelaporans';

    protected $fillable = [
        'pptk_id',
        'kegiatan_id',
        'subkegiatan_id',
        'jenis_belanja_id',
        'rekening_kegiatan',
        'tahun',
        'nominal_pagu',
        'nominal',
        'status',
        'file_path',
        'catatan',
        'laporan_tahunan_id',
    ];

    // =============================
    // Relasi
    // =============================

    public function pptk()
    {
        return $this->belongsTo(User::class, 'pptk_id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function subkegiatan()
    {
        return $this->belongsTo(Subkegiatan::class);
    }

    public function jenisBelanja()
    {
        return $this->belongsTo(JenisBelanjaPelaporan::class);
    }

    public function berkasPelaporans()
    {
        return $this->hasMany(BerkasPelaporan::class);
    }

    public function laporanTahunan()
    {
        return $this->belongsTo(LaporanTahunan::class);
    }
}
