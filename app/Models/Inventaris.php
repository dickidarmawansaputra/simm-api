<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventaris extends Model
{
    use SoftDeletes;
	
    protected $table = 'inventaris';

    protected $fillable = [
    	'kode_inventaris',
    	'nama_inventaris',
    	'kondisi_inventaris',
        'foto_inventaris',
    	'deskripsi_inventaris',
    	'masjid_id',
    	'pengguna_id',
    ];
}
