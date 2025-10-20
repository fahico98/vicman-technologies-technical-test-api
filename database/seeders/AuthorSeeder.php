<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('authors')->insert([
            [
                'name' => 'Gabriel García Márquez',
                'birth_date' => '1928',
                'top_work' => 'Cien años de soledad',
                'work_count' => 439,
                'open_library_key' => 'OL27363A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Franz Kafka',
                'birth_date' => '3 July 1883',
                'top_work' => 'Die Verwandlung',
                'work_count' => 1470,
                'open_library_key' => 'OL33146A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jules Verne',
                'birth_date' => '8 February 1828',
                'top_work' => 'Le Tour du Monde en Quatre-Vingts Jours',
                'work_count' => 10810,
                'open_library_key' => 'OL113611A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Albert Camus',
                'birth_date' => '7 November 1913',
                'top_work' => 'L’étranger',
                'work_count' => 485,
                'open_library_key' => 'OL124171A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Juan Rulfo',
                'birth_date' => '16 May 1917',
                'top_work' => 'Pedro Páramo',
                'work_count' => 54,
                'open_library_key' => 'OL199285A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Stieg Larsson',
                'birth_date' => '15 august 1954',
                'top_work' => 'Män som hatar kvinnor',
                'work_count' => 90,
                'open_library_key' => 'OL1414302A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ernesto Sabato',
                'birth_date' => '24 Jun 1911',
                'top_work' => 'El túnel',
                'work_count' => 184,
                'open_library_key' => 'OL2748652A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Edgar Allan Poe',
                'birth_date' => '19 January 1809',
                'top_work' => 'The Murders in the Rue Morgue',
                'work_count' => 4033,
                'open_library_key' => 'OL28127A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Agatha Christie',
                'birth_date' => '15 September 1890',
                'top_work' => 'The Mysterious Affair at Styles',
                'work_count' => 1754,
                'open_library_key' => 'OL27695A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Stephen King',
                'birth_date' => '21 September 1947',
                'top_work' => 'Carrie',
                'work_count' => 596,
                'open_library_key' => 'OL19981A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Julio Cortázar',
                'birth_date' => '26 August 1914',
                'top_work' => 'Rayuela',
                'work_count' => 485,
                'open_library_key' => 'OL2631008A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'George R. R. Martin',
                'birth_date' => '20 September 1948',
                'top_work' => 'A Game of Thrones',
                'work_count' => 370,
                'open_library_key' => 'OL234664A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'J. K. Rowling',
                'birth_date' => '31 July 1965',
                'top_work' => 'Harry Potter and the Philosopher\'s Stone',
                'work_count' => 420,
                'open_library_key' => 'OL23919A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Alexandre Dumas fils',
                'birth_date' => '1824',
                'top_work' => 'La dame aux camélias [novel]',
                'work_count' => 213,
                'open_library_key' => 'OL164363A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ernest Hemingway',
                'birth_date' => '21 July 1899',
                'top_work' => 'The Old Man and the Sea',
                'work_count' => 1126,
                'open_library_key' => 'OL13640A',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}