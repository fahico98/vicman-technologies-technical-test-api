<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Ejecuta el seeder.
     *
     * @return void
     * @author Fahibram Cárcamo
     */
    public function run(): void
    {
        User::factory(10)->create([
            'password' => bcrypt('Password12345*#')
        ]);
    }
}
