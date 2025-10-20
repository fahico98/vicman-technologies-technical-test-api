<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array
     * @author Fahibram Cárcamo
     */
    protected $fillable = [
        'author_id',
        'title',
        'open_library_cover_key',
        'first_publish_year',
        'units_available',
    ];

    /**
     * Obtiene los atributos que deben ser casteados.
     *
     * @var array
     * @author Fahibram Cárcamo
     */
    protected $casts = [
        'author_id' => 'integer',
        'first_publish_year' => 'integer',
        'units_available' => 'integer'
    ];

    /**
     * Obtener el autor del libro.
     *
     * @return BelongsTo
     * @author Fahibram Cárcamo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
