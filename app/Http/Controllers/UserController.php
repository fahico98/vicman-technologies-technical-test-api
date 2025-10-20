<?php

namespace App\Http\Controllers;

use App\Http\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Constructor.
     *
     * @param UserRepository $repository Repositorio de usuarios.
     * @author Fahibram Cárcamo
     */
    public function __construct(private UserRepository $repository)
    {}

    /**
     * Retorna la colección de todos los usuarios, con o sin paginación.
     *
     * @param Request $request Contenido de la petición HTTP entrante.
     * @return JsonResponse
     * @author Fahibram Cárcamo
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json(['data' => $this->repository->getAll($request)]);
    }
}
