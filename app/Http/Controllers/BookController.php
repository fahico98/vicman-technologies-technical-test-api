<?php

namespace App\Http\Controllers;

use App\Http\Repositories\BookRepository;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Services\BookService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Constructor.
     *
     * @param BookRepository $repository Repositorio de libros.
     * @param BookService $service Servicio de libros.
     * @author Fahibram Cárcamo
     */
    public function __construct(
        private BookRepository $repository,
        private BookService $service
    )
    {}

    /**
     * Retorna la colección de todos los libros, con o sin paginación.
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
     * Retorna una instancia del modelo Book según el valor del campo `id`.
     *
     * @param int $book_id Id del libro.
     * @return JsonResponse
     * @author Fahibram Cárcamo
     */
    public function show(int $book_id): JsonResponse
    {
        return response()->json(['data' => $this->repository->getOne($book_id)]);
    }

    /**
     * Crea un nuevo libro en la base de datos.
     *
     * @param StoreBookRequest $request Contenido de la petición HTTP entrante validada.
     * @return JsonResponse
     * @author Fahibram Cárcamo
     */
    public function store(StoreBookRequest $request): JsonResponse
    {
        try {
            $book = $this->service->store($request);

            return response()->json([
                'message' => 'Libro creado exitosamente',
                'data' => $book
            ]);

        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Error al crear el libro',
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza un libro existente en la base de datos.
     *
     * @param StoreBookRequest $request Contenido de la petición HTTP entrante validada.
     * @param int $book_id Id del libro.
     * @return JsonResponse
     * @author Fahibram Cárcamo
     */
    public function update(StoreBookRequest $request, int $book_id): JsonResponse
    {
        try {
            $book = $this->service->store($request, $book_id);

            return response()->json([
                'message' => 'Libro actualizado exitosamente',
                'data' => $book
            ]);

        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Error al actualizar el libro',
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina un libro de la base de datos.
     *
     * @param int $book_id Id del libro.
     * @return JsonResponse
     * @author Fahibram Cárcamo
     */
    public function destroy(int $book_id): JsonResponse
    {
        $this->repository->delete($book_id);
        return response()->json(['message' => 'Libro eliminado exitosamente']);
    }
}
