<?php

namespace App\Console\Commands\System;

use Exception;
use App\Traits\DirectorioTrait;
use Illuminate\Console\Command;

class MakeRepositoryCommand extends Command
{
    use DirectorioTrait;

    protected $signature   = 'make:repository {name}';
    protected $description = 'Crear repositorio';

    /**
     * Crear un nuevo repositorio.
     *
     * @return void
     * @author Daniel Beltrán
     */
    public function handle() {
        $name = $this->argument('name');
        $path = app_path("Http/Repositories/{$name}.php");

        if (file_exists($path)) {
            $this->error("El repositorio {$name} ya existe");
            return;
        }

        $this->crearDirectorio(app_path('Http/Repositories'));

        // Crear directorios si no existen
        $directories = explode('/', $name);
        $namespace   = 'App\Http\Repositories';

        for ($i = 0; $i < count($directories) - 1; $i++) {
            $directory_path = app_path('Http/Repositories/' . $directories[$i]);
            $namespace      .= '\\' . $directories[$i];
            $this->crearDirectorio($directory_path);
        }

        try {
            // Obtener el nombre del repositorio en limpio
            $repository_name = $directories[count($directories) - 1];

            // Obtener el nombre del modelo
            $model_name = str_replace('Repository', '', $repository_name);

            // Generar el namespace del modelo
            $model_namespace = str_replace('Repository', '', $name);
            $model_namespace = str_replace('/', '\\', $model_namespace);
            $model_namespace = 'App\Models\\' . $model_namespace;

            $file_content = <<<EOT
            <?php

            namespace $namespace;

            use $model_namespace;
            use App\Http\Repositories\BaseRepository;

            class $repository_name extends BaseRepository
            {
                /**
                 * Constructor.
                 *
                 * @param {$model_name} \$modelo
                 * @return void
                 */
                public function __construct($model_name \$modelo) {
                    parent::__construct(\$modelo);
                }
            }
            EOT;

            file_put_contents($path, $file_content);
            $this->info("Repositorio {$name} creado exitosamente");
        } catch (Exception $error) {
            $this->error('Error al crear el repositorio');
        }
    }
}
