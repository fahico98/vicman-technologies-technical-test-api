<?php

namespace App\Http\Repositories;

use App\Http\Services\PaginationService;
use App\Models\Author;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AuthorRepository extends BaseRepository
{
    const AUTHORS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param Author $model
     * @return void
     * @author Fahibram Cárcamo
     */
    public function __construct(Author $model) {
        parent::__construct($model);
    }

    /**
     * Retorna una colección con todos los autores, puede estar paginada o no.
     *
     * @param Request $request
     * @return Collection|LengthAwarePaginator
     * @author Fahibram Cárcamo
     */
    public function getAll(Request $request): Collection|LengthAwarePaginator
    {
        $authors_query = $this->model::select('*')->with('books')->orderBy('created_at', 'desc');

        if ($request->has('with_pagination') && !$request->filled('per_page')) {
            $request->merge(['per_page' => self::AUTHORS_PER_PAGE]);
        }

        return $request->has('with_pagination') && filter_var($request->with_pagination, FILTER_VALIDATE_BOOLEAN)
            ? PaginationService::paginate($request, $authors_query)
            : $authors_query->get();
    }

    /**
     * Retorna una instancia del modelo Author con todos sus libros asociados según el valor del
     * campo `id`.
     *
     * @param int $id Id del autor.
     * @return Author
     * @author Fahibram Cárcamo
     */
    public function getOne(int $id): Author
    {
        return $this->model::select('*')->with('books')->find($id);
    }
}