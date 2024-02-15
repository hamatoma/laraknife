<?php

namespace Database\Seeders;

use App\Models\User;
use App\Helpers\StringHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\UserController;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pw = StringHelper::createPassword();
        $email = 'administrator@example.com';
        \file_put_contents('.lrv.credentials', "$email\n$pw\n");
        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => $email,
            'password' => UserController::hash($email, $pw),
            'role_id' => 1
        ]);
        $email = 'guest@example.com';
        $pw =  StringHelper::createPassword();
        DB::table('users')->insert([
            'name' => 'Guest',
            'email' => $email,
            'password' => UserController::hash($email, $pw),,
            'role_id' => 4
        ]);
    }
}
