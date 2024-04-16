<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Memin',
            'email' => 'memin@gmail.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Sirius',
            'email' => 'sirius@gmail.com',
            'password' => Hash::make('password'),
        ]);
    }
}
