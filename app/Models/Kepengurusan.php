<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kepengurusan extends Model
{
    use SoftDeletes;
	
    protected $table = 'kepengurusan';

    protected $fillable = [
    	'nama',
	    'tempat_lahir',
	    'tanggal_lahir',
	    'jenis_kelamin',
	    'jabatan',
	    'periode',
	    'no_hp',
	    'email',
	    'masjid_id',
	    'pengguna_id',
    ];
}
