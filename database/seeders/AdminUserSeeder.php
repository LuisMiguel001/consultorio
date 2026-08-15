<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(

            [
                'email' => 'admin'
            ],

            [
                'name' => 'Administrador',
                'password' => Hash::make('1234'),
                'activo' => true,
            ]

        );

        if (!$user->hasRole('admin')) {

            $user->assignRole('admin');

        }
    }
}
