<?php

namespace App\Console\Commands;

use App\Http\Services\OpenLibraryService;
use Illuminate\Console\Command;

class PopulateAuthorSeederCommand extends Command
{
    /**
     * Constructor.
     *
     * @param OpenLibraryService $open_library_service Servicio de la API de Open Library.
     * @author Fahibram Cárcamo
     */
    public function __construct(private OpenLibraryService $open_library_service)
    {
        parent::__construct();
    }

    /**
     * Nombre y la firma del comando de consola.
     *
     * @var string
     * @author Fahibram Cárcamo
     */
    protected $signature = 'populate-author-seeder';

    /**
     * Descripción del comando de consola.
     *
     * @var string
     * @author Fahibram Cárcamo
     */
    protected $description = 'Llena los datos del seeder de autores.';

    /**
     * Ejecuta el comando de consola.
     *
     * @return void
     * @author Fahibram Cárcamo
     */
    public function handle(): void
    {
        $this->info('🔍 Obteniendo datos de autores desde Open Library API...');
        $this->info('');

        try {
            // Obtener datos de todos los autores conocidos
            $well_known_authors_data = $this->open_library_service->getWellKnownAuthors();

            // Procesar los datos de cada autor
            $authors_array = [];

            foreach ($well_known_authors_data as $author_data) {
                if (isset($author_data['docs'][0])) {
                    $author = $author_data['docs'][0];

                    // Extraer datos relevantes del autor
                    $name = $author['name'] ?? 'Unknown';
                    $birth_date = $author['birth_date'] ?? null;
                    $top_work = isset($author['top_work']) ? $author['top_work'] : null;
                    $work_count = $author['work_count'] ?? 0;
                    $open_library_key = $author['key'] ?? null;

                    // Construir array de datos
                    $authors_array[] = [
                        'name' => $name,
                        'birth_date' => $birth_date,
                        'top_work' => $top_work,
                        'work_count' => $work_count,
                        'open_library_key' => $open_library_key,
                        'created_at' => 'now()',
                        'updated_at' => 'now()',
                    ];

                    $this->line('👍 Procesado: ' . $name);
                }
            }

            if (empty($authors_array)) {
                $this->error('❌ No se obtuvieron datos de autores.');
                return;
            }

            // Generar el código del seeder
            $seeder_code = $this->generateSeederCode($authors_array);

            // Escribir el archivo del seeder
            $seeder_path = database_path('seeders\AuthorSeeder.php');
            file_put_contents($seeder_path, $seeder_code);

            $this->info('');
            $this->info('☑️ Seeder AuthorSeeder.php generado exitosamente!');
            $this->info('📝 Total de autores: ' . count($authors_array));
            $this->info('📂 Ubicación del seeder: ' . $seeder_path);

        } catch (\Exception $exception) {
            $this->error('❌ Error al generar el seeder: ' . $exception->getMessage());
            $this->error($exception->getTraceAsString());
        }
    }

    /**
     * Genera el código PHP del seeder con los datos de autores.
     *
     * @param array $authors_array Array de datos de autores.
     * @return string Código PHP del seeder.
     * @author Fahibram Cárcamo
     */
    private function generateSeederCode(array $authors_array): string
    {
        $authors_code = '';

        foreach ($authors_array as $author) {
            $authors_code .= "            [\n";
            $authors_code .= "                'name' => " . var_export($author['name'], true) . ",\n";
            $authors_code .= "                'birth_date' => " . var_export($author['birth_date'], true) . ",\n";
            $authors_code .= "                'top_work' => " . var_export($author['top_work'], true) . ",\n";
            $authors_code .= "                'work_count' => " . var_export($author['work_count'], true) . ",\n";
            $authors_code .= "                'open_library_key' => " . var_export($author['open_library_key'], true) . ",\n";
            $authors_code .= "                'created_at' => now(),\n";
            $authors_code .= "                'updated_at' => now(),\n";
            $authors_code .= "            ],\n";
        }

        return <<<PHP
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
        {$authors_code}
                ]);
            }
        }
        PHP;
    }
}
