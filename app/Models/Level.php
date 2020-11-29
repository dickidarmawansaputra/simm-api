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

    public static function isLevel($level_check)
    {
        $user_level = self::where(['pengguna_id'=> Auth::user()->id, 'level'=> $level_check])->first();
        return $user_level ? true : false;
    }
}
