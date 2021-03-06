<?php

namespace App\Models\People;

use Auth;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Household extends Model {

    protected $table = 'households';
    protected $fillable = ['name', 'uid', 'notes', 'aid', 'created_by', 'updated_by'];

    /**
     * A Household belongs to a account
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Models\Account\Account', 'aid');
    }

    /**
     * A Household has a head (Primary Person)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function head()
    {
        return $this->belongsTo('App\User', 'uid');
    }

    /**
     * A Household has many members.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function members()
    {
        return $this->belongsToMany('App\User', 'users_household', 'hid', 'uid');
    }

    /**
     * Household adults
     */
    public function adults()
    {
        $adults = [];
        foreach ($this->members as $member) {
            if (!$member->isStudent)
                $adults[] = $member->id;
        }
        return User::find($adults);
    }

    /**
     * Household students
     */
    public function students()
    {
        $students = [];
        foreach ($this->members as $member) {
            if ($member->isStudent)
                $students[] = $member->id;
        }
        return User::find($students);
    }

    /**
     * Display records last update_by + date
     *
     * @return string
     */
    public function displayUpdatedBy()
    {
        $user = User::find($this->updated_by);

        return ($user) ? '<span style="font-weight: 400">Last modified: </span>' . $this->updated_at->diffForHumans() . ' &nbsp; ' .
            '<span style="font-weight: 400">By:</span> ' . $user->name : "$this->updated_by";
    }

    /**
     * The "booting" method of the model.
     *
     * Overrides parent function
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        if (auth()->check()) {
            // create a event to happen on creating
            static::creating(function ($table) {
                $table->created_by = auth()->id();
                $table->updated_by = auth()->id();
                $table->aid = session('aid');
            });

            // create a event to happen on updating
            static::updating(function ($table) {
                $table->updated_by = auth()->id();
            });
        }
    }
}
