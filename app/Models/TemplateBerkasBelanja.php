<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateBerkasBelanja extends Model
{
    use HasFactory;

    protected $table = 'template_berkas_belanjas';

    protected $fillable = [
        'jenis_belanja_id',
        'nama_berkas'
    ];

    public function jenisBelanja()
    {
        return $this->belongsTo(JenisBelanjaPelaporan::class);
    }

    public function berkasPelaporan()
    {
        return $this->hasMany(BerkasPelaporan::class, 'template_berkas_id');
    }
}
