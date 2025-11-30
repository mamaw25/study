<?php

namespace App\Http\Controllers;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

class TimeSlotController extends Controller {
    public function index(){ return TimeSlot::all(); }
    public function store(Request $r){
        $r->validate(['start_time'=>'required','end_time'=>'required']);
        return TimeSlot::create($r->only(['start_time','end_time']));
    }
}

