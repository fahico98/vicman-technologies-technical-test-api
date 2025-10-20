<?php

namespace App\Http\Services;

use App\Http\Repositories\AuthorRepository;
use Illuminate\Http\Request;
use App\Models\Author;
use Exception;

/**
 * @property AuthorRepository $repository
 */
class AuthorService extends BaseService
{
    /**
     * Constructor.
     *
     * @param AuthorRepository $repository Repositorio de autores.
     * @return void
     */
    public function __construct(AuthorRepository $repository) {
        parent::__construct($repository);
    }

    /**
     * Guarda un autor con los datos de la petición entrante.
     *
     * @param Request $request Contenido de la petición entrante.
     * @param int|null $id Id del autor en caso de modificación.
     * @throws Exception
     * @author Fahibram Cárcamo
     */
    public function store(Request $request, int $id = null): Author
    {
        $open_library_service = new OpenLibraryService($this->repository);
        $author_data = $open_library_service->getAuthor($request->name);

        if (!is_null($author_data) && isset($author_data['docs'][0]['key'])) {
            $request->merge(['open_library_key' => $author_data['docs'][0]['key']]);
        }

        return is_null($id) ? $this->repository->create($request->all()) : $this->repository->update($id, $request->all());
    }
}