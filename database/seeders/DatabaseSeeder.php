<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
       // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Administrador',
            'a_paterno' => 'Tramusa',
            'a_materno' => 'Carrier',
            'alias' => 'Admin',
            'email' => 'admin@tramusacarrier.com.mx',
            'password' => Hash::make('TramusaCarrier'),
            'active' => true,
            'rol' => 'Administrador'
        ]);
    }
}
