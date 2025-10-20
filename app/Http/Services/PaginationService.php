<?php

namespace App\Http\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PaginationService
{
    /**
     * Aplica paginación a la consulta Eloquent dada según los datos de la consulta HTTP dada.
     *
     * @param Request $request Consulta HTTP entrante.
     * @param Builder $query Consulta Eloquent dada.
     * @author Fahibram Cárcamo
     */
    public static function paginate(Request $request, Builder $query): LengthAwarePaginator
    {
        return $request->has('per_page') && gettype($request->per_page) === 'integer' ? $query->paginate($request->per_page) : $query->paginate();
    }
}