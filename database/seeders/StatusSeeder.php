<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('status')->insert([
            'name' => 'aceptado',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('status')->insert([
            'name' => 'rechazado',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('status')->insert([
            'name' => 'pendiente',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('status')->insert([
            'name' => 'vencido',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('status')->insert([
            'name' => 'Activate',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('status')->insert([
            'name' => 'Offline',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('status')->insert([
            'name' => 'devoted',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('status')->insert([
            'name' => 'Returned',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('status')->insert([
            'name' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('status')->insert([
            'name' => 'cancel',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
    }
}
