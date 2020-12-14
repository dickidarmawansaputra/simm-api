<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Pengguna extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'pengguna';
    
    protected $fillable = [
        'nama', 'username', 'password', 'foto', 'masjid_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function level()
    {
        return $this->hasOne('App\Models\Level', 'pengguna_id', 'id');
    }

    public function masjid()
    {
        return $this->hasOne('App\Models\Masjid', 'id', 'masjid_id');
    }
}
