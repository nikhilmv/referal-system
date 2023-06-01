<?php

namespace Database\Seeders;

use App\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
        [
        'name' => 'Super Admin',
        'email' => 'superadmin@yopmail.com',
        'password' =>Hash::make(1234),
        'level' => '1',
        'refereal_code' => 'ZUP59V',
        'parent_user_id' => null,
        'points' => 10, 
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
        ]);
  
    }
}
