<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasApiTokens, Notifiable;
    protected $primaryKey = 'user_id';
    protected $fillable = ['role_id','full_name','email','id_number','password','mobile_number'];
    protected $hidden = ['password','remember_token'];
    public function role(){ return $this->belongsTo(Role::class, 'role_id','role_id'); }
    public function reservations(){ return $this->hasMany(Reservation::class, 'user_id','user_id'); }
    
    public function setPasswordAttribute($value){ $this->attributes['password'] = bcrypt($value); }
    public $incrementing = true;
    protected $keyType = 'int';
}

