<?php

namespace App\Http\Services;

use App\Http\Repositories\LoanRepository;
use App\Http\Services\BaseService;

/**
 * @property LoanRepository $repositorio
 */
class LoanService extends BaseService
{
    /**
     * Constructor.
     *
     * @param LoanRepository $repositorio
     * @return void
     */
    public function __construct(LoanRepository $repositorio) {
        parent::__construct($repositorio);
    }
}