<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kegiatan extends Model
{
    use SoftDeletes;
	
    protected $table = 'kegiatan';

    protected $fillable = [
    	'nama_kegiatan',
    	'deskripsi_kegiatan',
    	'jenis_kegiatan',
    	'foto_kegiatan',
    	'tanggal_waktu_kegiatan',
        'pemateri',
    	'masjid_id',
    	'pengguna_id',
    ];
}
