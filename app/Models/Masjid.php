<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Masjid extends Model
{
    use SoftDeletes;

    protected $table = 'masjid';

    protected $fillable = [
    	'nama_masjid',
    	'tipologi_masjid',
        'deskripsi_masjid',
    	'alamat_masjid',
    	'kecamatan',
    	'kelurahan',
    	'gambar',
    	'tanggal_tahun_berdiri',
    	'status_tanah',
    	'luas_tanah',
    	'luas_bangunan',
    ];
}
