<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'NameUser' => 'exampleuser',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'must_change_password' => false,
            'password_recovery_token' => Str::random(60),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'NameUser' => 'secretaria',
            'email' => 'secretary@example.com',
            'password' => Hash::make('password'),
            'role'=>'secretary',
            'must_change_password' => false,
            'password_recovery_token' => Str::random(60),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'NameUser' => 'Admin',
            'email' => 'Admin@example.com',
            'password' => Hash::make('password'),
            'role'=>'admin',
            'must_change_password' => false,
            'password_recovery_token' => Str::random(60),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
