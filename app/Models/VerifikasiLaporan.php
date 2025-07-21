<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifikasiLaporan extends Model
{
    use HasFactory;

    protected $table = 'verifikasi_laporans';

    protected $fillable = [
        'pelaporan_id',
        'verifikator_id',
        'role_verifikator',
        'tanggal_verifikasi',
        'catatan',
        'status',
    ];

    protected $dates = ['tanggal_verifikasi'];

    public function pelaporan()
    {
        return $this->belongsTo(Pelaporan::class, 'pelaporan_id');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verifikator_id');
    }
}
