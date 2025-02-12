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
            'name' => '結衣',
            'email' => 'ar@gaki',
            'password' => Hash::make('77777777'),
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

        DB::table('users')->insert([
            'name' => '朱里紗',
            'email' => 'd@te',
            'password' => Hash::make('77777777'),
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

        DB::table('users')->insert([
            'name' => '千夏',
            'email' => 'k@no',
            'password' => Hash::make('77777777'),
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        
            
    }
}
