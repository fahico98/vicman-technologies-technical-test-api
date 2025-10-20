<?php

namespace App\Http\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    /**
     * Constructor.
     *
     * @param Model $model Modelo.
     * @return void
     * @author Fahibram Cárcamo
     */
    public function __construct(protected Model $model) {}

    /**
     * Obtener todos los registros.
     *
     * @param Request $request Datos para filtrar.
     * @return Collection|LengthAwarePaginator
     * @author Fahibram Cárcamo
     */
    public function getAll(Request $request): Collection|LengthAwarePaginator {
        return $this->model::get();
    }

    /**
     * Obtener registro.
     *
     * @param int $id ID del registro.
     * @return Model|null
     * @author Fahibram Cárcamo
     */
    public function getOne(int $id): Model | null {
        return $this->model::find($id);
    }

    /**
     * Crear registro.
     *
     * @param array $datos Datos a guardar.
     * @return Model
     * @author Fahibram Cárcamo
     */
    public function create(array $datos): Model {
        return $this->model::create($datos);
    }

    /**
     * Crear un registro si no existe.
     *
     * @param array $search Parámetros a buscar.
     * @param array $optional_parameters Parámetros pueden crearse sin que afecten la búsqueda.
     * @return Model
     * @author Fahibram Cárcamo
     * @author Fahibram Cárcamo
     */
    public function createIfNotExists(array $search, array $optional_parameters = []): Model {
        return $this->model::firstOrCreate($search, $optional_parameters);
    }

    /**
     * Actualizar registro.
     *
     * @param int $id ID del registro.
     * @param array $data Datos a guardar.
     * @return Model
     * @author Fahibram Cárcamo
     */
    public function update(int $id, array $data): Model {
        $instance = $this->model::find($id);
        $instance->fill($data);
        $instance->save();
        return $instance;
    }

    /**
     * Actualizar registro en base a una o más condiciones.
     *
     * @param array $conditions Condiciones para buscar el registro.
     * @param array $data Datos a guardar.
     * @return Model
     * @author Fahibram Cárcamo
     */
    public function updateByCondition(array $conditions, array $data): Model {
        $instance = $this->model::where($conditions);
        $instance->fill($data);
        $instance->save();
        return $instance;
    }

    /**
     * Actualizar varios registros a la vez.
     *
     * @param array $ids ID's de los registros.
     * @param array $data Datos a guardar.
     * @return void
     * @author Fahibram Cárcamo
     */
    public function updateMany(array $ids, array $data): void {
        $this->model::whereIn('id', $ids)->update($data);
    }

    /**
     * Crear o actualizar registro.
     *
     * @param array $conditions Condiciones para buscar el registro.
     * @param array $data Datos a guardar o actualizar.
     * @return Model
     * @author Fahibram Cárcamo
     */
    public function updateOrCreate(array $conditions, array $data): Model {
        return $this->model::updateOrCreate($conditions, $data);
    }

    /**
     * Eliminar registro.
     *
     * @param int $id ID del registro.
     * @param ?bool $definitely Indica si se debe eliminar definitivamente.
     * @return void
     * @author Fahibram Cárcamo
     */
    public function delete(int $id, ?bool $definitely = false): void {
        if ($definitely) {
            $this->model::withTrashed()->find($id)->forceDelete();
            return;
        }

        $this->model::find($id)->delete();
    }

    /**
     * Eliminar varios registros a la vez.
     *
     * @param array $ids ID's de los registros.
     * @return void
     * @author Fahibram Cárcamo
     */
    public function deleteMany(array $ids): void {
        $this->model::whereIn('id', $ids)->delete();
    }
}
