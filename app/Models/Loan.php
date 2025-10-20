<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array
     * @author Fahibram Cárcamo
     */
    protected $fillable = [
        'user_id',
        'book_id',
        'date',
        'return_date',
    ];

    /**
     * Obtiene los atributos que deben ser casteados.
     *
     * @var array
     * @author Fahibram Cárcamo
     */
    protected $casts = [
        'user_id' => 'integer',
        'book_id' => 'integer',
        'date' => 'date',
        'return_date' => 'date',
    ];

    /**
     * Obtener el usuario asociado al préstamo.
     *
     * @return BelongsTo
     * @author Fahibram Cárcamo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtener el libro asociado al préstamo.
     *
     * @return BelongsTo
     * @author Fahibram Cárcamo
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
