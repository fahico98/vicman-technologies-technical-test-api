<?php

namespace App\Http\Services;

use App\Http\Repositories\UserRepository;
use App\Http\Services\BaseService;

/**
 * @property UserRepository $repositorio
 */
class UserService extends BaseService
{
    /**
     * Constructor.
     *
     * @param UserRepository $repositorio
     * @return void
     */
    public function __construct(UserRepository $repositorio) {
        parent::__construct($repositorio);
    }
}