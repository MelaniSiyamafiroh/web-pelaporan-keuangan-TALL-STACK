<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaguAnggaran extends Model
{
    use HasFactory;

    protected $table = 'pagu_anggarans';

    protected $fillable = [
        'tahun',
        'total_pagu',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'total_pagu' => 'float',
    ];

    // =============================
    // Scope
    // =============================

    public function scopeTahunAktif($query)
    {
        return $query->where('tahun', session('tahun_aktif'));
    }
}
