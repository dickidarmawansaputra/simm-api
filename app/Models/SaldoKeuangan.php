<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaldoKeuangan extends Model
{
	use SoftDeletes;

    protected $table = 'saldo_keuangan';

    protected $fillable = [
    	'masjid_id',
    	'saldo',
    ];

    public function history()
    {
        return $this->hasMany('App\Models\Keuangan', 'masjid_id', 'masjid_id');
    }
}
