<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berkas extends Model
{
    use HasFactory;

    protected $table = 'berkas';

    protected $fillable = [
        'pelaporan_id',
        'nama_berkas',
        'file_path',
        'keterangan',
        'uploaded_by',
    ];

    public function pelaporan()
    {
        return $this->belongsTo(Pelaporan::class, 'pelaporan_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
