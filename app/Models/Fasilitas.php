<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fasilitas extends Model
{
	use SoftDeletes;
	
    protected $table = 'fasilitas';

    protected $fillable = [
    	'nama_fasilitas',
    	'deskripsi_fasilitas',
    	'foto_fasilitas',
        'kondisi_fasilitas',
    	'masjid_id',
    	'pengguna_id',
    ];
}
