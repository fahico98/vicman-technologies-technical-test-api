<?php

namespace App\Http\Repositories;

use App\Http\Services\PaginationService;
use App\Models\Book;
use App\Http\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BookRepository extends BaseRepository
{
    const BOOKS_PER_PAGE = 10;

    /**
     * Constructor.
     *
     * @param Book $model
     * @return void
     * @author Fahibram Cárcamo
     */
    public function __construct(Book $model) {
        parent::__construct($model);
    }

    /**
     * Retorna una colección con todos los libros, puede estar paginada o no.
     *
     * @param Request $request
     * @return Collection|LengthAwarePaginator
     * @author Fahibram Cárcamo
     */
    public function getAll(Request $request): Collection|LengthAwarePaginator
    {
        $books_query = $this->model::select('*')->with('author')->orderBy('created_at', 'desc');

        if ($request->has('with_pagination') && !$request->filled('per_page')) {
            $request->merge(['per_page' => self::BOOKS_PER_PAGE]);
        }

        return $request->has('with_pagination') && filter_var($request->with_pagination, FILTER_VALIDATE_BOOLEAN)
            ? PaginationService::paginate($request, $books_query)
            : $books_query->get();
    }

    /**
     * Retorna una instancia del modelo Book con su autor asociado según el valor del campo `id`.
     *
     * @param int $id Id del libro.
     * @return Book
     * @author Fahibram Cárcamo
     */
    public function getOne(int $id): Book
    {
        return $this->model::select('*')->with('author')->find($id);
    }
}
