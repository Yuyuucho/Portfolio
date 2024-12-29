<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete();

        DB::table('users')->insert([
            'name' => '太郎',
            'email' => 'b@nana',
            'password' => 7777,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

        DB::table('users')->insert([
            'name' => '雄太',
            'email' => 'or@nge',
            'password' => 7777,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

        DB::table('users')->insert([
            'name' => '琉奈',
            'email' => '@pple',
            'password' => 7777,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        
            
    }
}
