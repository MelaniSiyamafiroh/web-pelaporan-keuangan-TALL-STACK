<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasPelaporan extends Model
{
    use HasFactory;

    protected $table = 'berkas_pelaporan';

    protected $fillable = [
        'pelaporan_id',
        'template_berkas_id',
        'nama_file',
        'path_file',
        'size_file',
    ];

    public function pelaporan()
    {
        return $this->belongsTo(Pelaporan::class);
    }

    public function templateBerkas()
    {
        return $this->belongsTo(TemplateBerkasBelanja::class, 'template_berkas_id');
    }
}
