<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisBelanjaPelaporan extends Model
{
    use HasFactory;

    protected $table = 'jenis_belanja_pelaporans';

    protected $fillable = ['nama'];

    public function templateBerkas()
    {
        return $this->hasMany(TemplateBerkasBelanja::class, 'jenis_belanja_id');
    }

    public function pelaporans()
    {
        return $this->hasMany(Pelaporan::class, 'jenis_belanja_id');
    }
}
