<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            'password' => Hash::make('77777777'),
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

        DB::table('users')->insert([
            'name' => '雄太',
            'email' => 'or@nge',
            'password' => Hash::make('77777777'),
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

        DB::table('users')->insert([
            'name' => '琉奈',
            'email' => 'a@pple',
            'password' => Hash::make('77777777'),
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        
            
    }
}
