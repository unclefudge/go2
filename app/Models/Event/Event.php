<?php

namespace App\Models\Event;

use App\User;
use App\Models\People\People;
use Carbon\Carbon;
use Camroncade\Timezone\Facades\Timezone;
use Illuminate\Database\Eloquent\Model;

class Event extends Model {

    protected $table = 'events';
    protected $fillable = [
        'name', 'recur', 'code', 'day', 'start', 'end', 'grades', 'background',
        'notes', 'status', 'aid', 'created_by', 'updated_by'];
    protected $dates = ['start', 'end'];

    /**
     * A Event belongs to a account
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Models\Account\Account', 'aid');
    }


    /**
     * A Event has many EventInstance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function instances()
    {
        return $this->hasMany('App\Models\Event\EventInstance', 'eid');
    }

    /**
     * A Event has many EventInstance between 2 dates.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function betweenUTCDates($date1, $date2)
    {
        return EventInstance::where('eid', $this->id)->whereBetween('start', [$date1, $date2])->get();
    }

    /**
     * Student Attendees in last X weeks
     */
    public function studentAttendance($weeks, $new = false)
    {
        $now = Carbon::now();
        $from = Carbon::now()->subWeeks($weeks);
        $instance_ids = $this->betweenUTCDates($from->format('Y-m-d'), $now->format('Y-m-d'))->pluck('id')->toArray();
        $attendance = Attendance::whereIn('eid', $instance_ids)->get();
        $students = [];
        if ($attendance) {
            foreach ($attendance as $attend) {
                if ($attend->person->isStudent && !in_array($attend->pid, $students)) {
                    if ($new && $attend->person->firstEvent($this->id)->start->isSameDay($attend->instance->start))
                        $students[] = $attend->pid;
                    elseif (!$new)
                        $students[] = $attend->pid;
                }
            }
        }

        return People::find($students);
    }

    /**
     * Top Student Attendees for last X weeks
     */
    public function studentTopAttendance($weeks)
    {
        $now = Carbon::now();
        $from = Carbon::now()->subWeeks($weeks);
        $instance_ids = $this->betweenUTCDates($from->format('Y-m-d'), $now->format('Y-m-d'))->pluck('id')->toArray();
        $attendance = Attendance::whereIn('eid', $instance_ids)->get();
        $students = [];
        if ($attendance) {
            foreach ($attendance as $attend) {
                if ($attend->person->isStudent) {
                    if (isset($students[$attend->pid]))
                        $students[$attend->pid] = $students[$attend->pid] + 1;
                    else
                        $students[$attend->pid] = 1;
                }
            }
        }
        arsort($students, true);

        return $students;
    }

    /**
     * Student MIA (Missing in Action) for last X weeks
     */
    public function studentMIA($weeks)
    {
        $active_students = $this->studentAttendance($weeks)->pluck('id')->toArray();
        $instance_ids = $this->instances->pluck('id')->toArray();
        $previous_attenders = Attendance::whereIn('eid', $instance_ids)->pluck('pid')->toArray();
        $people = People::find($previous_attenders);
        $mia = [];
        foreach ($people as $person) {
            if ($person->status && $person->isStudent && !in_array($person->id, $active_students))
                $mia[] = $person->id;
        }

        return People::find($mia);
    }


    /**
     * Get Background Path (getter)
     */
    public function getBackgroundPathAttribute()
    {
        $path = "/image/" . $this->attributes['aid'] . '/events/';

        return ($this->attributes['background']) ? $path . $this->attributes['background'] . '?' . rand(1, 32000) : '/img/bg-event.jpg';
    }

    /**
     * Get Background Path (getter)
     */
    public function getBackgroundMedPathAttribute()
    {
        $path = "/image/" . $this->attributes['aid'] . '/events/m';

        return ($this->attributes['background']) ? $path . $this->attributes['background'] . '?' . rand(1, 32000) : '/img/bg-event.jpg';
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
     * Convert date to User Local Timezone format
     *
     * @return Carbon date object
     */
    public function getStartLocalAttribute()
    {
        return $this->start->timezone(session('tz'));
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
