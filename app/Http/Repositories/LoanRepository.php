<?php

namespace App\Http\Repositories;

use App\Models\Loan;
use App\Http\Repositories\BaseRepository;

class LoanRepository extends BaseRepository
{
    /**
     * Constructor.
     *
     * @param Loan $modelo
     * @return void
     */
    public function __construct(Loan $modelo) {
        parent::__construct($modelo);
    }
}