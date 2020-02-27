<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UsersTableSeeder extends Seeder
{
    public function run()
    {
      DB::transaction(function () { 
         $user_id = DB::table('users')->insertGetId([
            'name' => 'Admin',
            'last_name' => 'Digitalicemos',
            'email' => 'admin@digitalicemos.com',
            'password' => Hash::make('10n1cD1g1t4l'),
            'full_name' => 'Admin Digitalicemos',
            'administrator' => true,
            'created_by' => 1,
            'updated_by' => 1,
         ]);
         DB::table('records')->insert([
            'record_id' => $user_id,
            'form_code' => 'F100',
            'form_title' => 'Alta de usuario',
            'name' => 'users',
            'period' => null,
            'due_date' => null,
            't1' => 'Admin Digitalicemos',
            't2' => '',
            't3' => null,
            'created_by' => 1,
            'updated_by' => 1
         ]);
      });
    }
}
