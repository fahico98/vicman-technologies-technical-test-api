<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     *
     * @return bool
     * @author Fahibram Cárcamo
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     *
     * @return array
     * @author Fahibram Cárcamo
     */
    public function rules(): array
    {
        return [
            'author_id' => 'required|integer|exists:authors,id',
            'title' => 'required|string|max:255',
            'first_publish_year' => 'nullable|integer|min:1|max:' . date('Y'),
            'units_available' => 'nullable|integer|min:0'
        ];
    }

    /**
     * Obtiene los mensajes de error personalizados para las reglas de validación.
     *
     * @return array
     * @author Fahibram Cárcamo
     */
    public function messages(): array
    {
        return [
            'author_id.required' => 'El autor es obligatorio.',
            'author_id.integer' => 'El ID del autor debe ser un número entero.',
            'author_id.exists' => 'El autor seleccionado no existe.',
            'title.required' => 'El título del libro es obligatorio.',
            'title.string' => 'El título debe ser una cadena de texto.',
            'title.max' => 'El título no puede exceder los 255 caracteres.',
            'first_publish_year.integer' => 'El año de primera publicación debe ser un número entero.',
            'first_publish_year.min' => 'El año de primera publicación debe ser al menos 1.',
            'first_publish_year.max' => 'El año de primera publicación no puede ser mayor al año actual.',
            'units_available.integer' => 'Las unidades disponibles deben ser un número entero.',
            'units_available.min' => 'Las unidades disponibles no pueden ser negativas.'
        ];
    }
}
