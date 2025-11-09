<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Person;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'ccardenas',
            'password' => bcrypt('admin'),
            'role_id' => Role::where('name', 'Administrador del Sistema')->first()->id,
            'person_id' => Person::where('email', '1512755@senati.pe')->first()->id,
        ]);
    }
}
