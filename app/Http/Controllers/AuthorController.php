<?php

namespace App\Http\Controllers;

use App\Http\Repositories\AuthorRepository;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Services\AuthorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class AuthorController extends Controller
{
    /**
     * Constructor.
     *
     * @param AuthorRepository $repository Repositorio de autores.
     * @param AuthorService $service Servicio de autores.
     * @author Fahibram Cárcamo
     */
    public function __construct(
        private AuthorRepository $repository,
        private AuthorService $service
    )
    {}

    /**
     * Retorna la colección de todos los autores, con o sin paginación.
     *
     * @param Request $request Contenido de la petición HTTP entrante.
     * @return JsonResponse
     * @author Fahibram Cárcamo
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json(['data' => $this->repository->getAll($request)]);
    }

    /**
     * Retorna una instancia del modelo Author según el valor del campo `id`.
     *
     * @param int $author_id Id del autor.
     * @return JsonResponse
     * @author Fahibram Cárcamo
     */
    public function show(int $author_id): JsonResponse
    {
        return response()->json(['data' => $this->repository->getOne($author_id)]);
    }

    /**
     * Crea un nuevo autor en la base de datos.
     *
     * @param StoreAuthorRequest $request Contenido de la petición HTTP entrante validada.
     * @return JsonResponse
     * @author Fahibram Cárcamo
     */
    public function store(StoreAuthorRequest $request): JsonResponse
    {
        try {
            $author = $this->service->store($request);

            return response()->json([
                'message' => 'Autor creado exitosamente',
                'data' => $author
            ], 201);

        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Error al crear el autor',
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza un autor existente en la base de datos.
     *
     * @param StoreAuthorRequest $request Contenido de la petición HTTP entrante validada.
     * @param int $author_id Id del autor.
     * @return JsonResponse
     * @author Fahibram Cárcamo
     */
    public function update(StoreAuthorRequest $request, int $author_id): JsonResponse
    {
        try {
            $author = $this->service->store($request, $author_id);

            return response()->json([
                'message' => 'Autor actualizado exitosamente',
                'data' => $author
            ]);

        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Error al actualizar el autor',
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina un autor de la base de datos.
     *
     * @param int $author_id Id del autor.
     * @return JsonResponse
     * @author Fahibram Cárcamo
     */
    public function destroy(int $author_id): JsonResponse
    {
        $this->repository->delete($author_id);
        return response()->json(['message' => 'Autor eliminado exitosamente']);
    }
}
