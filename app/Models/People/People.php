<?php

namespace App\Models\People;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    protected $table = 'people';
    protected $fillable = [
        'firstname', 'lastname', 'type', 'gender', 'dob', 'email', 'phone', 'instagram',
        'address', 'address2', 'suburb', 'state', 'postcode', 'country',
        'grade', 'school_id', 'photo', 'wwc_no', 'wwc_exp', 'wwc_verified', 'wwc_verified_by',
        'notes', 'minhub', 'status', 'aid',  'created_by', 'updated_by'];
    protected $dates = ['dob', 'wwc_exp', 'wwc_verified'];

    /**
     * A People belongs to a account
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Models\Account\Account', 'aid');
    }


    /**
     * A People May have many users (who own this profile)
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'users_people', 'pid', 'uid');
    }

    /**
     * A People May belongs to a school
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\People\School', 'school_id');
    }

    /**
     * A People WWC May be Verified by a User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wwcVerifiedBy()
    {
        return People::find($this->wwc_verified_by);
    }

    /**
     * Get the Full name (first + last)   (getter)
     *
     * @return string;
     */
    public function getNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    /**
     * Get Age (getter)
     */
    public function getAgeAttribute()
    {
        return Carbon::parse($this->attributes['dob'])->age;
    }

    /**
     * Get Timezone  (getter)
     *
     * @return string;
     */
    public function getTimezoneAttribute()
    {
        return $this->account->timezone;
    }

    /**
     * Get the suburb, state, postcode  (getter)
     */
    public function getAddressFormattedAttribute()
    {
        $string = '';

        if ($this->attributes['address'])
            $string = strtoupper($this->attributes['address']) . '<br>';

        $string .= strtoupper($this->attributes['suburb']);
        if ($this->attributes['suburb'] && $this->attributes['state'])
            $string .= ', ';
        if ($this->attributes['state'])
            $string .= $this->attributes['state'];
        if ($this->attributes['postcode'])
            $string .= ' ' . $this->attributes['postcode'];

        return ($string) ? $string : '-';
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
            });

            // create a event to happen on updating
            static::updating(function ($table) {
                $table->updated_by = auth()->id();
            });
        }
    }
}
