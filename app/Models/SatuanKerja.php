<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatuanKerja extends Model
{
    use HasFactory;

    protected $table = 'satuan_kerjas';

    protected $fillable = [
        'nama',
        'kode',
    ];

    public function laporanTahunans()
    {
        return $this->hasMany(LaporanTahunan::class, 'satuan_kerja_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'satuan_kerja_id');
    }

    public function kegiatans()
    {
        return $this->hasMany(Kegiatan::class, 'satuan_kerja_id');
    }
}
