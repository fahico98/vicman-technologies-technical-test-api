<?php

namespace App\Http\Controllers;

use App\Http\Repositories\BookRepository;
use App\Http\Repositories\LoanRepository;
use App\Http\Requests\StoreLoanRequest;
use Illuminate\Http\JsonResponse;
use Exception;

class LoanController extends Controller
{
    /**
     * Constructor.
     *
     * @param LoanRepository $repository Repositorio de prestamos.
     * @param BookRepository $book_repository Repositorio de libros.
     * @author Fahibram Cárcamo
     */
    public function __construct(
        private LoanRepository $repository,
        private BookRepository $book_repository
    )
    {}

    /**
     * Registra un nuevo prestamo de libro.
     *
     * @param StoreLoanRequest $request
     * @return JsonResponse
     * @author Fahibram Cárcamo
     */
    public function store(StoreLoanRequest $request): JsonResponse
    {
        try {
            $book = $this->book_repository->getOne($request->book_id);

            if ($book->units_available === 0) {
                return response()->json([
                    'message' => 'El libro no tiene unidades disponibles'
                ]);
            }

            $loan = $this->repository->create($request->all());
            $this->book_repository->update($book->id, ['units_available' => $book->units_available - 1]);

            return response()->json([
                'message' => 'Prestamo registrado exitosamente',
                'data' => $loan
            ], 201);

        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Error al registrar el prestamo',
                'error' => $exception->getMessage()
            ], 500);
        }
    }
}
