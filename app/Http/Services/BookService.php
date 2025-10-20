<?php

namespace App\Http\Services;

use App\Http\Repositories\AuthorRepository;
use App\Http\Repositories\BookRepository;
use Illuminate\Http\Request;
use App\Models\Book;
use Exception;

/**
 * @property BookRepository $repository
 */
class BookService extends BaseService
{
    /**
     * Constructor.
     *
     * @param BookRepository $repository Repositorio de libros.
     * @param AuthorRepository $author_repository Repositorio de autores.
     * @return void
     */
    public function __construct(
        BookRepository $repository,
        private AuthorRepository $author_repository)
    {
        parent::__construct($repository);
    }

    /**
     * Guarda un libro con los datos de la petición entrante.
     *
     * @param Request $request Contenido de la petición entrante.
     * @param int|null $id Id del libro en caso de modificación.
     * @throws Exception
     * @author Fahibram Cárcamo
     */
    public function store(Request $request, int $id = null): Book
    {
        $author = $this->author_repository->getOne($request->author_id);

        $open_library_service = new OpenLibraryService($this->author_repository);
        $book_data = $open_library_service->getBook($request->title, $author->open_library_key);

        if (!is_null($book_data) && isset($book_data['docs'][0]['cover_edition_key'])) {
            $request->merge(['open_library_cover_key' => $book_data['docs'][0]['cover_edition_key']]);
        }

        return is_null($id) ? $this->repository->create($request->all()) : $this->repository->update($id, $request->all());
    }
}
