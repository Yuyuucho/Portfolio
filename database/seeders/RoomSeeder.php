<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rooms')->delete();

        DB::table('rooms')->insert([
        'roomname' => 'room1',
        'roompass' => '7777',
        'created_at' => new DateTime(),
        'updated_at' => new DateTime(),
        ]);

        DB::table('rooms')->insert([
            'roomname' => 'room2',
            'roompass' => '7777',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

        DB::table('rooms')->insert([
            'roomname' => 'room3',
            'roompass' => '7777',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}
