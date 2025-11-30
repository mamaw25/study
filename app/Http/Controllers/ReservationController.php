<?php

namespace App\Http\Controllers;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReservationController extends Controller {
    
    public function index(Request $r){
        $user = $r->user();
        if($user->role->role_name == 'admin'){
            return Reservation::with(['user','room','timeslot'])->get();
        }
        return $user->reservations()->with(['room','timeslot'])->get();
    }

    // show
    public function show($id){ return Reservation::with(['user','room','timeslot'])->findOrFail($id); }

    // create
    public function store(Request $r){
        $r->validate([
            'room_id'=>'required|exists:rooms,room_id',
            'timeslot_id'=>'required|exists:time_slots,timeslot_id',
            'date'=>'required|date|after_or_equal:today',
            'purpose'=>'nullable|string'
        ]);

        // check room status
        $room = Room::findOrFail($r->room_id);
        if($room->status !== 'available'){
            return response()->json(['error'=>'Room not available'],422);
        }

        // Conflict logic
        $conflict = Reservation::where('room_id',$r->room_id)
            ->where('timeslot_id',$r->timeslot_id)
            ->where('date',$r->date)
            ->whereIn('status',['pending','approved'])
            ->exists();

        if($conflict){
            return response()->json(['error'=>'Timeslot already booked for this room and date'],422);
        }

        $reservation = Reservation::create([
            'user_id' => $r->user()->user_id,
            'room_id' => $r->room_id,
            'timeslot_id' => $r->timeslot_id,
            'date' => $r->date,
            'purpose' => $r->purpose,
            'status'=> 'pending'
        ]);

        return response()->json($reservation,201);
    }

    // admin update
    public function updateStatus(Request $r, $id){
        $r->validate(['status'=>['required', Rule::in(['pending','approved','rejected','cancelled'])]]);
        $reservation = Reservation::findOrFail($id);
        
        if($r->status === 'approved'){
            $conflict = Reservation::where('room_id',$reservation->room_id)
                ->where('timeslot_id',$reservation->timeslot_id)
                ->where('date',$reservation->date)
                ->whereIn('status',['approved'])
                ->where('reservation_id','!=',$reservation->reservation_id)
                ->exists();
            if($conflict) return response()->json(['error'=>'Another reservation already approved for this slot'],422);
        }
        $reservation->status = $r->status;
        $reservation->save();
        return $reservation;
    }

    // cancel
    public function cancel(Request $r, $id){
        $reservation = Reservation::findOrFail($id);
        if($reservation->user_id !== $r->user()->user_id && $r->user()->role->role_name !== 'admin'){
            return response()->json(['error'=>'Not allowed'],403);
        }
        $reservation->status = 'cancelled';
        $reservation->save();
        return $reservation;
    }

    // delete
    public function destroy(Request $r, $id){
        $reservation = Reservation::findOrFail($id);
        if($r->user()->role->role_name !== 'admin') return response()->json(['error'=>'Forbidden'],403);
        $reservation->delete();
        return response()->json(['message'=>'deleted']);
    }
}

