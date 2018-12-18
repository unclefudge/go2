<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'last_ip', 'last_login', 'password_reset', 'status', 'created_by', 'updated_by',
    ];

    /**
     * A User May have many people profiles they own this.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'users_people', 'pid', 'uid');
    }

    /**
     * A User May have many people (profiles they may own)
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function people()
    {
        return $this->belongsToMany('App\Models\People\People', 'users_people', 'uid', 'pid');
    }

    /**
     * Get user fullname if linked to person else username  (getter)
     */
    public function getNameAttribute()
    {
        return ($this->people->first()) ? $this->people->first()->name : $this->attributes['username'];
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
