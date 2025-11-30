<?php

namespace App\Http\Controllers;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller {
    public function index(){ return Room::all(); }
    public function show($id){ return Room::findOrFail($id); }

    public function store(Request $r){
        $r->validate(['room_name'=>'required|unique:rooms','capacity'=>'required|integer','status'=>'nullable']);
        return Room::create($r->only(['room_name','capacity','status']));
    }
    public function update(Request $r, $id){
        $room = Room::findOrFail($id);
        $room->update($r->only(['room_name','capacity','status']));
        return $room;
    }
    public function destroy($id){
        Room::destroy($id);
        return response()->json(['message'=>'deleted']);
    }
}

