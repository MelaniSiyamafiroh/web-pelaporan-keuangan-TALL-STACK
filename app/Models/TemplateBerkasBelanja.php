<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateBerkasBelanja extends Model
{
    use HasFactory;

    // ✅ Tambahkan ini
    protected $table = 'template_berkas_belanja';

    protected $fillable = [
        'jenis_belanja' => 'spj_gu',
        'nama_berkas' => 'Kuitansi',
    ];
}
