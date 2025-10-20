<?php

namespace App\Http\Repositories;

use App\Http\Services\PaginationService;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class UserRepository extends BaseRepository
{
    const USERS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param User $model Modelo de usuario.
     * @return void
     * @author Fahibram Cárcamo
     */
    public function __construct(User $model) {
        parent::__construct($model);
    }

    /**
     * Retorna una colección con todos los usuarios, puede estar paginada o no.
     *
     * @param Request $request
     * @return Collection|LengthAwarePaginator
     * @author Fahibram Cárcamo
     */
    public function getAll(Request $request): Collection|LengthAwarePaginator
    {
        $users_query = $this->model::select('*');

        if ($request->has('with_pagination') && !$request->filled('per_page')) {
            $request->merge(['per_page' => self::USERS_PER_PAGE]);
        }

        return $request->has('with_pagination') && filter_var($request->with_pagination, FILTER_VALIDATE_BOOLEAN)
            ? PaginationService::paginate($request, $users_query)
            : $users_query->get();
    }
}