<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanAuthorSeederCommand extends Command
{
    /**
     * Nombre y la firma del comando de consola.
     *
     * @var string
     * @author Fahibram Cárcamo
     */
    protected $signature = 'clean-author-seeder';

    /**
     * Descripción del comando de consola.
     *
     * @var string
     * @author Fahibram Cárcamo
     */
    protected $description = 'Limpiar el seeder de autores.';

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
        
        class AuthorSeeder extends Seeder
        {
            /**
             * Run the database seeds.
             */
            public function run(): void
            {
                DB::table('authors')->insert([
                    // ...
                ]);
            }
        }
        PHP;

        // Escribir el archivo del seeder.
        $seeder_path = database_path('seeders\AuthorSeeder.php');
        file_put_contents($seeder_path, $seeder_code);
        $this->info('☑️ Seeder AuthorSeeder.php limpiado exitosamente!');
    }
}
