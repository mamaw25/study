<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TimeSlot extends Model {
    protected $primaryKey='timeslot_id';
    protected $fillable=['start_time','end_time'];
    public function reservations(){ return $this->hasMany(Reservation::class,'timeslot_id','timeslot_id'); }
}

