<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreLoanRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:users,id',
            'book_id' => 'required|integer|exists:books,id',
            'date' => 'required|date|before_or_equal:today',
            'return_date' => [
                'required',
                'date',
                'after:date',
                function ($attribute, $value, $fail) {
                    // Obtener la fecha de préstamo del request
                    $loanDateValue = request()->input('date');

                    // Verificar que la fecha de préstamo exista y sea válida
                    if (!$loanDateValue) {
                        return;
                    }

                    try {
                        $loanDate = Carbon::parse($loanDateValue);
                        $returnDate = Carbon::parse($value);

                        // Verificar que la diferencia no sea mayor a 30 días
                        $daysDifference = $returnDate->diffInDays($loanDate);

                        if ($daysDifference > 30) {
                            $fail('La fecha de devolución no puede ser mayor a 30 días después de la fecha de préstamo.');
                        }
                    } catch (\Exception $e) {
                        // Si hay error al parsear las fechas, no hacer nada
                        // Las validaciones 'date' ya manejarán el formato incorrecto
                        return;
                    }
                },
            ],
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
            'user_id.required' => 'El usuario es obligatorio.',
            'user_id.integer' => 'El ID del usuario debe ser un número entero.',
            'user_id.exists' => 'El usuario seleccionado no existe.',
            'book_id.required' => 'El libro es obligatorio.',
            'book_id.integer' => 'El ID del libro debe ser un número entero.',
            'book_id.exists' => 'El libro seleccionado no existe.',
            'date.required' => 'La fecha de préstamo es obligatoria.',
            'date.date' => 'La fecha de préstamo debe ser una fecha válida.',
            'date.before_or_equal' => 'La fecha de préstamo no puede ser futura.',
            'return_date.required' => 'La fecha de devolución es obligatoria.',
            'return_date.date' => 'La fecha de devolución debe ser una fecha válida.',
            'return_date.after' => 'La fecha de devolución debe ser posterior a la fecha de préstamo.',
        ];
    }
}
