<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class RoomUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('room_user')->delete();

        /*DB::table('room_user')->insert([
            'user_id' => '1',
            'room_id' => '3',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

        DB::table('room_user')->insert([
            'user_id' => '2',
            'room_id' => '3',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

        DB::table('room_user')->insert([
            'user_id' => '3',
            'room_id' => '1',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]); */
    
    }
}
