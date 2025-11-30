<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

DB::table('users')->insert([
  [
    'role_id'=>1,
    'full_name'=>'Admin User',
    'email'=>'admin@example.com',
    'id_number'=>'ADMIN001',
    'password'=>bcrypt('password'),
    'mobile_number'=>'09171234567',
    'created_at'=>now(),'updated_at'=>now()
  ],
  [
    'role_id'=>2,
    'full_name'=>'Regular User',
    'email'=>'user@example.com',
    'id_number'=>'USER001',
    'password'=>bcrypt('password'),
    'mobile_number'=>'09179876543',
    'created_at'=>now(),'updated_at'=>now()
  ],
]);

