<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Level extends Model
{
	use SoftDeletes;
	
    protected $table = 'level';

    protected $fillable = [
    	'pengguna_id', 'level',
    ];
}
