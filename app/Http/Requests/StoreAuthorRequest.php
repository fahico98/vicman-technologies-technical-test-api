<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuthorRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'birth_date' => 'nullable|string|max:255',
            'top_work' => 'nullable|string|max:255',
            'work_count' => 'nullable|integer|min:0'
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
            'name.required' => 'El nombre del autor es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede exceder los 255 caracteres.',
            'birth_date.string' => 'La fecha de nacimiento debe ser una cadena de texto.',
            'birth_date.max' => 'La fecha de nacimiento no puede exceder los 255 caracteres.',
            'top_work.string' => 'La obra principal debe ser una cadena de texto.',
            'top_work.max' => 'La obra principal no puede exceder los 255 caracteres.',
            'work_count.integer' => 'El conteo de obras debe ser un número entero.',
            'work_count.min' => 'El conteo de obras no puede ser negativo.'
        ];
    }
}
