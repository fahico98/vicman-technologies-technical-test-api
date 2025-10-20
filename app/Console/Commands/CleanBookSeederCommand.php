<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanBookSeederCommand extends Command
{
    /**
     * Nombre y la firma del comando de consola.
     *
     * @var string
     * @author Fahibram Cárcamo
     */
    protected $signature = 'clean-book-seeder';

    /**
     * Descripción del comando de consola.
     *
     * @var string
     * @author Fahibram Cárcamo
     */
    protected $description = 'Limpiar el seeder de libros.';

    /**
     * Ejecuta el comando de consola.
     *
     * @return void
     * @author Fahibram Cárcamo
     */
    public function handle()
    {
        // Código del seeder.
        $seeder_code = <<<PHP
        <?php
        
        namespace Database\Seeders;
        
        use Illuminate\Support\Facades\DB;
        use Illuminate\Database\Seeder;
        
        class BookSeeder extends Seeder
        {
            /**
             * Run the database seeds.
             */
            public function run(): void
            {
                DB::table('books')->insert([
                    // ...
                ]);
            }
        }
        PHP;

        // Escribir el archivo del seeder.
        $seeder_path = database_path('seeders\BookSeeder.php');
        file_put_contents($seeder_path, $seeder_code);
        $this->info('☑️ Seeder BookSeeder.php limpiado exitosamente!');
    }
}
