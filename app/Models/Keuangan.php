<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Keuangan extends Model
{
	use SoftDeletes;
	
    protected $table = 'keuangan';

    protected $fillable = [
    	'jenis_keuangan',
    	'sumber',
    	'jumlah',
    	'keterangan',
    	'tanggal',
    	'masjid_id',
    	'pengguna_id',
    ];

    public function saldo()
    {
        return $this->hasOne('App\Models\SaldoKeuangan', 'masjid_id', 'masjid_id');
    }

    public function history()
    {
        return $this->hasMany('App\Models\Keuangan', 'jenis_keuangan', 'jenis_keuangan')->orderBy('tanggal', 'desc');
    }
}
