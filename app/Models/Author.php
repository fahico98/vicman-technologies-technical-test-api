<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array
     * @author Fahibram Cárcamo
     */
    protected $fillable = [
        'name',
        'birth_date',
        'top_work',
        'work_count',
        'open_library_key',
    ];

    /**
     * Obtiene los atributos que deben ser casteados.
     *
     * @return array
     * @author Fahibram Cárcamo
     */
    protected $casts = [
        'work_count' => 'integer',
    ];

    /**
     * Obtener los libros del autor.
     *
     * @return HasMany
     * @author Fahibram Cárcamo
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
