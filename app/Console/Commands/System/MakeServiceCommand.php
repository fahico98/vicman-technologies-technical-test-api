<?php

namespace App\Console\Commands\System;

use Exception;
use App\Traits\DirectorioTrait;
use Illuminate\Console\Command;

class MakeServiceCommand extends Command
{
    use DirectorioTrait;

    protected $signature   = 'make:service {name} {--no-repo}';
    protected $description = 'Crear servicio';

    /**
     * Crear un nuevo servicio.
     *
     * @return void
     * @author Daniel Beltrán
     */
    public function handle() {
        $name = $this->argument('name');
        $path = app_path("Http/Services/{$name}.php");

        if (file_exists($path)) {
            $this->error("El servicio {$name} ya existe");
            return;
        }

        $this->crearDirectorio(app_path('Http/Services'));

        // Crear directorios si no existen
        $directories = explode('/', $name);
        $namespace   = 'App\Http\Services';

        for ($i = 0; $i < count($directories) - 1; $i++) {
            $directory_path = app_path('Http/Services/' . $directories[$i]);
            $namespace      .= '\\' . $directories[$i];
            $this->crearDirectorio($directory_path);
        }

        try {
            if ($this->option('no-repo')) {
                $file_content = <<<EOT
                <?php

                namespace App\Http\Services;

                class $name
                {
                    //
                }
                EOT;
            } else {
                // Obtener el nombre del servicio en limpio
                $service_name = $directories[count($directories) - 1];

                // Obtener el nombre del repositorio
                $repository_name = str_replace('Service', 'Repository', $service_name);

                // Generar el namespace del repositorio
                $repository_namespace = str_replace('Service', 'Repository', $name);
                $repository_namespace = str_replace('/', '\\', $repository_namespace);
                $repository_namespace = 'App\Http\Repositories\\' . $repository_namespace;

                $file_content = <<<EOT
                <?php

                namespace $namespace;

                use $repository_namespace;
                use App\Http\Services\BaseService;

                /**
                 * @property $repository_name \$repositorio
                 */
                class $service_name extends BaseService
                {
                    /**
                     * Constructor.
                     *
                     * @param {$repository_name} \$repositorio
                     * @return void
                     */
                    public function __construct($repository_name \$repositorio) {
                        parent::__construct(\$repositorio);
                    }
                }
                EOT;
            }

            file_put_contents($path, $file_content);
            $this->info("Servicio {$name} creado exitosamente");
        } catch (Exception $error) {
            $this->error('Error al crear el servicio');
        }
    }
}
