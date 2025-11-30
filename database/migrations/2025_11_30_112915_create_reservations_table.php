<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('reservations', function (Blueprint $table) {
        $table->id('reservation_id');
        $table->foreignId('user_id')->constrained('users','user_id')->cascadeOnDelete();
        $table->foreignId('room_id')->constrained('rooms','room_id')->cascadeOnDelete();
        $table->foreignId('timeslot_id')->constrained('time_slots','timeslot_id')->cascadeOnDelete();
        $table->date('date');
        $table->string('purpose')->nullable();
        $table->enum('status',['pending','approved','rejected','cancelled'])->default('pending');
        $table->timestamps();
        
        $table->unique(['room_id','timeslot_id','date'],'room_timeslot_date_unique');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
