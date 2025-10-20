<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait DirectorioTrait
{
    /**
     * Crea un directorio si no existe.
     *
     * @param string $ruta Ruta del directorio.
     * @return void
     * @author Daniel Beltrán
     */
    public function crearDirectorio(string $ruta): void {
        if (!is_dir($ruta)) {
            mkdir($ruta);
        }
    }

    /**
     * Crear los directorios que componen una ruta.
     *
     * Los directorios se crean dentro de la carpeta storage, a no ser que se indique lo contrario.
     *
     * @param string $ruta Ruta.
     * @param bool $crear_en_app Indica si se deben crear los directorios en la carpeta app.
     * @return void
     * @author Daniel Beltrán
     */
    public function generarDirectorios(string $ruta, bool $crear_en_app = false): void {
        $directorios      = explode('/', $ruta);
        $nuevo_directorio = '';

        foreach ($directorios as $directorio) {
            $nuevo_directorio .= $directorio . '/';

            if ($crear_en_app) {
                $this->crearDirectorio(app_path($nuevo_directorio));
            } else {
                $this->crearDirectorio(storage_path($nuevo_directorio));
            }
        }
    }

    /**
     * Cambiar el nombre de un directorio.
     *
     * @param string $ruta Ruta del directorio.
     * @param string $nombre Nuevo nombre del directorio.
     * @return void
     * @author Daniel Beltrán
     */
    public function cambiarNombreDirectorio(string $ruta, string $nombre): void {
        if (is_dir($ruta)) {
            rename($ruta, dirname($ruta) . '/' . $nombre);
        }
    }

    /**
     * Eliminar un directorio y sus archivos contenidos.
     *
     * @param string $ruta Ruta del directorio.
     * @return void
     * @author Daniel Beltrán
     */
    public function eliminarDirectorio(string $ruta): void {
        if (is_dir($ruta)) {
            File::deleteDirectory($ruta);
        }
    }
}
