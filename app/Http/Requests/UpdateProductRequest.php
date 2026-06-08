<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateProductRequest extends FormRequest
{
    private int $nameMaxStringLength = 15;
    private int $descriptionMaxStringLength = 2000;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:' . $this->nameMaxStringLength,
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:' . $this->descriptionMaxStringLength,
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.string' => 'El nombre del producto debe ser una cadena de texto.',
            'name.max' => 'El nombre del producto no puede exceder los ' . $this->nameMaxStringLength . ' caracteres.',
            'category_id.required' => 'La categoría del producto es obligatoria.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
            'description.required' => 'La descripción del producto es obligatoria.',
            'description.string' => 'La descripción del producto debe ser una cadena de texto.',
            'description.max' => 'La descripción del producto no puede exceder los ' . $this->descriptionMaxStringLength . ' caracteres.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator, response()->json([
                'message' => 'La validación ha fallado.',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
