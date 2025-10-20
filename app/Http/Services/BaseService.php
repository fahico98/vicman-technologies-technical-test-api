<?php

namespace App\Http\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Http\Repositories\BaseRepository;

abstract class BaseService
{
    /**
     * Constructor.
     *
     * @param BaseRepository $repository Repositorio.
     * @return void
     * @author Daniel Beltrán
     */
    public function __construct(protected BaseRepository $repository) {}

    /**
     * Acceder al repositorio.
     *
     * @return BaseRepository
     * @author Daniel Beltrán
     */
    public function getRepositorio(): BaseRepository {
        return $this->repository;
    }

    /**
     * Guardar registro.
     *
     * @param Request $request Contenido de la petición.
     * @return Model
     * @author Daniel Beltrán
     */
    public function guardar(Request $request): Model {
        $campos_excluidos = ['_token', '_method', 'id'];

        if ($request->filled('campos_excluidos')) {
            $campos_excluidos = array_unique([...$campos_excluidos, ...$request->campos_excluidos]);
        }

        if ($request->filled('id')) {
            $registro = $this->repository->update($request->id, $request->except($campos_excluidos));
        } else {
            $registro = $this->repository->crear($request->except($campos_excluidos));
        }

        return $registro;
    }

    /**
     * Obtener los datos referenciados en el historial de cambios.
     *
     * @param Collection $historial Historial de cambios.
     * @param string $campo_id ID del campo referenciado.
     * @param string $campo_filtro Campo para filtrar los datos.
     * @param BaseRepository $repositorio Repositorio de los datos referenciados.
     * @return Collection
     * @author Daniel Beltrán
     */
    public function obtenerDatosReferenciados(Collection $historial, string $campo_id, string $campo_filtro, BaseRepository $repositorio): Collection {
        $antiguos_ids = $historial->pluck('old_values')->pluck($campo_id)->filter()->unique()->toArray();
        $nuevos_ids   = $historial->pluck('new_values')->pluck($campo_id)->filter()->unique()->toArray();
        $ids          = array_unique([...$antiguos_ids, ...$nuevos_ids]);
        return $repositorio->getTodo(new Request([$campo_filtro => $ids]));
    }
}
