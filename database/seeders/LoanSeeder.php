<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @author Fahibram Cárcamo
     */
    public function run(): void
    {
        // Obtener IDs de usuarios y libros existentes
        $user_ids = DB::table('users')->pluck('id')->toArray();
        $book_ids = DB::table('books')->pluck('id')->toArray();

        $loans = [];

        for ($i = 0; $i < 20; $i++) {
            // Seleccionar usuario y libro aleatorios
            $user_id = $user_ids[array_rand($user_ids)];
            $book_id = $book_ids[array_rand($book_ids)];

            // Generar fecha de préstamo aleatoria (últimos 3 meses)
            $loan_date = Carbon::now()->subDays(rand(0, 90));

            // Generar fecha de devolución aleatoria (entre 1 y 30 días después del préstamo)
            $return_date = (clone $loan_date)->addDays(rand(1, 30));

            $loans[] = [
                'user_id' => $user_id,
                'book_id' => $book_id,
                'date' => $loan_date->format('Y-m-d'),
                'return_date' => $return_date->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        DB::table('loans')->insert($loans);
    }
}
