<?php

namespace App\Http\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    /**
     * Constructor.
     *
     * @param Model $modelo Modelo.
     * @return void
     * @author Daniel Beltrán
     */
    public function __construct(protected Model $modelo) {}

    /**
     * Obtener todos los registros.
     *
     * @param Request $request Datos para filtrar.
     * @return Collection
     * @author Daniel Beltrán
     */
    public function getTodo(Request $request): Collection {
        return $this->modelo::get();
    }

    /**
     * Obtener registro.
     *
     * @param int $id ID del registro.
     * @return Model|null
     * @author Daniel Beltrán
     */
    public function getUno(int $id): Model | null {
        return $this->modelo::find($id);
    }

    /**
     * Crear registro.
     *
     * @param array $datos Datos a guardar.
     * @return Model
     * @author Daniel Beltrán
     */
    public function crear(array $datos): Model {
        return $this->modelo::create($datos);
    }

    /**
     * Crear un registro si no existe.
     *
     * @param array $busqueda Parámetros a buscar.
     * @param array $parametros_opcionales Parámetros pueden crearse sin que afecten la búsqueda.
     * @return Model
     * @author Daniel Beltrán
     * @author Fahibram Cárcamo
     */
    public function crearSiNoExiste(array $busqueda, array $parametros_opcionales = []): Model {
        return $this->modelo::firstOrCreate($busqueda, $parametros_opcionales);
    }

    /**
     * Actualizar registro.
     *
     * @param int $id ID del registro.
     * @param array $datos Datos a guardar.
     * @return Model
     * @author Daniel Beltrán
     */
    public function actualizar(int $id, array $datos): Model {
        $registro = $this->modelo::find($id);
        $registro->fill($datos);
        $registro->save();
        return $registro;
    }

    /**
     * Actualizar registro en base a una o más condiciones.
     *
     * @param array $condiciones Condiciones para buscar el registro.
     * @param array $datos Datos a guardar.
     * @return Model
     * @author Daniel Beltrán
     */
    public function actualizarPorCondicion(array $condiciones, array $datos): Model {
        $registro = $this->modelo::where($condiciones);
        $registro->fill($datos);
        $registro->save();
        return $registro;
    }

    /**
     * Actualizar varios registros a la vez.
     *
     * @param array $ids ID's de los registros.
     * @param array $datos Datos a guardar.
     * @return void
     * @author Daniel Beltrán
     */
    public function actualizarVarios(array $ids, array $datos): void {
        $this->modelo::whereIn('id', $ids)->update($datos);
    }

    /**
     * Crear o actualizar registro.
     *
     * @param array $condiciones Condiciones para buscar el registro.
     * @param array $datos Datos a guardar o actualizar.
     * @return Model
     * @author Daniel Beltrán
     */
    public function crearOActualizar(array $condiciones, array $datos): Model {
        return $this->modelo::updateOrCreate($condiciones, $datos);
    }

    /**
     * Eliminar registro.
     *
     * @param int $id ID del registro.
     * @param ?bool $definitivamente Indica si se debe eliminar definitivamente.
     * @return void
     * @author Daniel Beltrán
     */
    public function eliminar(int $id, ?bool $definitivamente = false): void {
        if ($definitivamente) {
            $this->modelo::withTrashed()->find($id)->forceDelete();
            return;
        }

        $this->modelo::find($id)->delete();
    }

    /**
     * Eliminar varios registros a la vez.
     *
     * @param array $ids ID's de los registros.
     * @return void
     * @author Daniel Beltrán
     */
    public function eliminarVarios(array $ids): void {
        $this->modelo::whereIn('id', $ids)->delete();
    }

    /**
     * Obtener el historial de cambios.
     *
     * @param int $id ID del registro.
     * @return Collection
     * @author Daniel Beltrán
     */
    public function historialCambios(int $id): Collection {
        return $this->getUno($id)->audits()
            ->select('id', 'user_type', 'user_id', 'event', 'auditable_type', 'auditable_id', 'old_values', 'new_values', 'created_at')
            ->with('user:id,nombres,apellidos')
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    /**
     * Obtener el último registro.
     *
     * Obtiene el registro que tenga la fecha de creación más reciente.
     * Si se recibe la propiedad 'campo' en el request, se ordenará por ese campo.
     *
     * @param ?Request $request Contenido de la petición.
     * @return ?Model
     * @author Daniel Beltrán
     */
    public function obtenerUltimo(?Request $request = null): ?Model {
        return $this->modelo::latest($request->campo ?? null)->first();
    }
}
