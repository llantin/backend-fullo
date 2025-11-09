<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Person;
use App\Models\User;


class PeopleSeeder extends Seeder
{
    public function run(): void
    {
        Person::create([
            'name' => 'Carlos',
            'last_name' => 'Cardenas',
            'email' => '1512755@senati.pe',
            'phone' => '970335192',
            'type' => 'Usuario',
            'identification_type' => 'DNI',
            'identification_number' => '60978317',
        ]);
        Person::create([
            'name' => 'Frank',
            'last_name' => 'Fuentes',
            'email' => '1122334@senati.pe',
            'phone' => '970335192',
            'type' => 'Usuario',
            'identification_type' => 'DNI',
            'identification_number' => '11223344',
        ]);
        Person::create([
            'name' => 'Nicolas',
            'last_name' => 'Meza',
            'email' => '4332211@senati.pe',
            'phone' => '970335192',
            'type' => 'Usuario',
            'identification_type' => 'DNI',
            'identification_number' => '12341234',
        ]);
        Person::create([
            'name' => 'Josue',
            'last_name' => 'Yalta',
            'email' => '1212123@senati.pe',
            'phone' => '970335192',
            'type' => 'Usuario',
            'identification_type' => 'DNI',
            'identification_number' => '12344321',
        ]);
        Person::create([
            'name' => 'Hector',
            'last_name' => 'Ramos',
            'email' => '3121212@senati.pe',
            'phone' => '970335192',
            'type' => 'Usuario',
            'identification_type' => 'DNI',
            'identification_number' => '43211234',
        ]);


    }
}
