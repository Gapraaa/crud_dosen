<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';
    protected $primaryKey = 'nidn';

    protected $fillable = [
        'nidn', 'nama_dosen', 'tgl_mulai_tugas', 'jenjang_pendidikan', 'bidang_keilmuan', 'foto_dosen'
    ];
}
