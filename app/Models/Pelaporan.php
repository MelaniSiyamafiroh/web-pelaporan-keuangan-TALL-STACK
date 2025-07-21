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
        'jenis_belanja',
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
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function subkegiatan()
    {
        return $this->belongsTo(Subkegiatan::class, 'subkegiatan_id');
    }

    public function laporanTahunan()
    {
        return $this->belongsTo(LaporanTahunan::class, 'laporan_tahunan_id');
    }

    public function berkas()
    {
        return $this->hasMany(Berkas::class, 'pelaporan_id');
    }

    public function berkasPelaporan()
{
    return $this->hasMany(BerkasPelaporan::class);
}


    public function verifikasi()
    {
        return $this->hasMany(VerifikasiLaporan::class, 'pelaporan_id');
    }

    // =============================
    // Scope
    // =============================

    public function scopeTahunAktif($query)
    {
        return $query->whereHas('subkegiatan', function ($q) {
            $q->where('tahun_anggaran', session('tahun_aktif'));
        });
    }
}
