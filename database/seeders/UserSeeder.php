<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $users = [
            [
                'name' => 'ali',
                'email' => 'ali@ali.com',
                'password' => Hash::make(11111111),
            ],
            [
                'name' => 'Test',
                'email' => 'test@ali.com',
                'password' => Hash::make(11111111),
            ]
        ];
        User::insert($users);
    }
}
