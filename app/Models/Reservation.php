<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Reservation extends Model {
    protected $primaryKey='reservation_id';
    protected $fillable=['user_id','room_id','timeslot_id','date','purpose','status'];
    public function user(){ return $this->belongsTo(User::class,'user_id','user_id'); }
    public function room(){ return $this->belongsTo(Room::class,'room_id','room_id'); }
    public function timeslot(){ return $this->belongsTo(TimeSlot::class,'timeslot_id','timeslot_id'); }
}

