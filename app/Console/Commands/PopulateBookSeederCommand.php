<?php

namespace App\Console\Commands;

use App\Http\Services\OpenLibraryService;
use Illuminate\Console\Command;

class PopulateBookSeederCommand extends Command
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
    protected $signature = 'populate-book-seeder';

    /**
     * Descripción del comando de consola.
     *
     * @var string
     * @author Fahibram Cárcamo
     */
    protected $description = 'Llena los datos del seeder de libros.';

    /**
     * Ejecuta el comando de consola.
     *
     * @return void
     * @author Fahibram Cárcamo
     */
    public function handle(): void
    {
        $this->info('🔍 Obteniendo datos de libros desde Open Library API...');
        $this->info('');

        try {
            // Obtener datos de todos los libros de los autores
            $all_books_data = $this->open_library_service->getAllAhthorsBooks();

            // Procesar los datos de cada libro
            $books_array = [];

            foreach ($all_books_data as $book_data) {
                // Verificar si hay un author_id asociado
                if (!isset($book_data['author_id'])) {
                    continue;
                }

                $author_id = $book_data['author_id'];

                // Procesar los documentos de libros (máximo 10 por autor)
                if (isset($book_data['docs']) && is_array($book_data['docs'])) {
                    // Tomar solo los primeros 10 libros o todos si hay menos de 10
                    $books_to_process = array_slice($book_data['docs'], 0, 10);

                    foreach ($books_to_process as $book) {
                        // Extraer datos relevantes del libro
                        $title = $book['title'] ?? 'Unknown';
                        $open_library_cover_key = isset($book['cover_edition_key']) ? $book['cover_edition_key'] : null;
                        $first_publish_year = $book['first_publish_year'] ?? null;

                        // Construir array de datos
                        $books_array[] = [
                            'author_id' => $author_id,
                            'title' => $title,
                            'open_library_cover_key' => $open_library_cover_key,
                            'first_publish_year' => $first_publish_year,
                            'created_at' => 'now()',
                            'updated_at' => 'now()',
                        ];

                        $this->line('👍 Procesado: ' . $title);
                    }
                }
            }

            if (empty($books_array)) {
                $this->error('❌ No se obtuvieron datos de libros.');
                return;
            }

            // Generar el código del seeder
            $seeder_code = $this->generateSeederCode($books_array);

            // Escribir el archivo del seeder
            $seeder_path = database_path('seeders\BookSeeder.php');
            file_put_contents($seeder_path, $seeder_code);

            $this->info('');
            $this->info('☑️ Seeder BookSeeder.php generado exitosamente!');
            $this->info('📝 Total de libros: ' . count($books_array));
            $this->info('📂 Ubicación del seeder: ' . $seeder_path);

        } catch (\Exception $exception) {
            $this->error('❌ Error al generar el seeder: ' . $exception->getMessage());
            $this->error($exception->getTraceAsString());
        }
    }

    /**
     * Genera el código PHP del seeder con los datos de libros.
     *
     * @param array $books_array Array de datos de libros.
     * @return string Código PHP del seeder.
     * @author Fahibram Cárcamo
     */
    private function generateSeederCode(array $books_array): string
    {
        $books_code = '';

        foreach ($books_array as $book) {
            $books_code .= "            [\n";
            $books_code .= "                'author_id' => " . var_export($book['author_id'], true) . ",\n";
            $books_code .= "                'title' => " . var_export($book['title'], true) . ",\n";
            $books_code .= "                'open_library_cover_key' => " . var_export($book['open_library_cover_key'], true) . ",\n";
            $books_code .= "                'first_publish_year' => " . var_export($book['first_publish_year'], true) . ",\n";
            $books_code .= "                'units_available' => " . rand(5, 30) . ",\n";
            $books_code .= "                'created_at' => now(),\n";
            $books_code .= "                'updated_at' => now(),\n";
            $books_code .= "            ],\n";
        }

        return <<<PHP
        <?php

        namespace Database\\Seeders;

        use Illuminate\\Support\\Facades\\DB;
        use Illuminate\\Database\\Seeder;

        class BookSeeder extends Seeder
        {
            /**
             * Run the database seeds.
             */
            public function run(): void
            {
                DB::table('books')->insert([
        {$books_code}
                ]);
            }
        }
        PHP;
    }
}
