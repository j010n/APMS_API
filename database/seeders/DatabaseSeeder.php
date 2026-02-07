<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(2)->create();

          User::factory()->create([
            'name' => 'JAO admin',
            'email' => 'joao@gmail.com',
            'password' => Hash::make('senha'), // same bcrypt('password'),

            'phone' => '11999999999',
            'tel' => '1133333333',
            'cpf' => '12345678901',
            'rg' => '123456789',
            'sex' => 'M',
            'birthdate' => '1990-01-01',

            'country' => 'Brasil',
            'state' => 'São Paulo',
            'city' => 'São Paulo',
            'affiliated' => true,
        ]);
    }
}
