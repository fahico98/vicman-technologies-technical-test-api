<?php

namespace App\Http\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    /**
     * Constructor.
     *
     * @param User $modelo Modelo de usuario.
     * @return void
     */
    public function __construct(User $modelo) {
        parent::__construct($modelo);
    }
}