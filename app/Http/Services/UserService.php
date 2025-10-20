<?php

namespace App\Http\Services;

use App\Http\Repositories\UserRepository;
use App\Http\Services\BaseService;

/**
 * @property UserRepository $repository
 */
class UserService extends BaseService
{
    /**
     * Constructor.
     *
     * @param UserRepository $repository
     * @return void
     */
    public function __construct(UserRepository $repository) {
        parent::__construct($repository);
    }
}