<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    private int $nameMaxStringLength = 15;
    private int $descriptionMaxStringLength = 2000;
    
    // Función principal para obtener todos los productos (con paginado)
    public function index(Request $request) {
        $perPage = $request->query('per_page', 10); // Número de productos por página, por defecto 10
        $page = $request->query('page', 1); // Página actual, por defecto 1
        $offset = $page * $perPage; // El offset es el número de productos a saltar
        
        $products = Product::skip($offset)
                            ->take($perPage)
                            ->get(); // Obtener los productos con el offset y el límite
        
        return response()->json($products);
    }

    // Store
    public function store(Request $request) {
        try{
            // Llamamos a la función de validación para validar los datos del producto
            $this->validateProductData($request);
            $product = Product::create($request->all());
            return response()->json($product, Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    // Update
    public function update(UpdateProductRequest $request, int $id) {
        try {
            $product = Product::findOrFail($id);
            $product->update($request->all());
            return response()->json($product, Response::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    // función de validación para datos de producto
    private function validateProductData(Request $request) {
        return $request->validate([
            'name' => 'required|string|max:' . $this->nameMaxStringLength,
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:' . $this->descriptionMaxStringLength,
        ],
        [
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.string' => 'El nombre del producto debe ser una cadena de texto.',
            'name.max' => "El nombre del producto no puede exceder los $this->nameMaxStringLength caracteres.",
            'category_id.required' => 'La categoría del producto es obligatoria.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
            'description.required' => 'La descripción del producto es obligatoria.',
            'description.string' => 'La descripción del producto debe ser una cadena de texto.',
            'description.max' => "La descripción del producto no puede exceder los $this->descriptionMaxStringLength caracteres.",
        ]);
    }

    // Delete
    public function delete(int $id) {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return response()->json(['message' => 'Producto eliminado correctamente.'], Response::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
