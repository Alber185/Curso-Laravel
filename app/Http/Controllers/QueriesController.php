<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueriesController extends Controller
{
    public function get() {
        $products = Product::all();
        $categories = Category::all();
        return response()->json([
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function getById(int $id): JsonResponse {
        $product = Product::query()->find($id);
        if (!$product) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }
        return response()->json($product);
    }

    public function getNames(): JsonResponse {
        $products = Product::query()->select('name')
                                    ->orderBy('name', 'asc')
                                    ->get();
        return response()->json($products);
    }

    public function filterNamesAndCategory(string $name, string $category): JsonResponse {
        $products = Product::query()->select('id', 'name', 'category_id')
                                    ->with('category')
                                    //Este es un tipo de OR hecho con orwhere
                                    ->where('name', 'like', "%$name%")->orwhere('description', 'like', "%$name%") 
                                    ->whereHas('category', function ($query) use ($category) {
                                    //Y este es otro tipo de OR hecho con operador Elvis ?:
                                        $query->where('name', 'like', "%".($category ?: 'none')."%");
                                    })
                                    ->orderBy('name', 'asc')
                                    ->get();
        return response()->json($products);

    }

    public function advancedSeach(Request $request): JsonResponse {
        $products = Product::query()->where(function ($query) use ($request) {
            if ($request->has('name')) {
                $query->where('name', 'like', "%{$request->input('name')}%");
            }
            if ($request->has('description')) {
                $query->where('description', 'like', "%{$request->input('description')}%");
            }
            if ($request->has('category_name')) {
                $query->whereHas('category', function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->input('category_name')}%");
                });
            }
        })->get();

        return response()->json($products);

    }

    public function join() {
        $products = Product::join('categories', 'products.category_id', '=', 'categories.id')
                            ->select('products.*', 'categories.name as category_name')
                            ->get();
        return response()->json($products);
    }

    // Productos por categoría
    public function groupBy() {
        $products = Product::join('categories', 'products.category_id', '=', 'categories.id')
                            ->select('categories.id', 'categories.name', DB::raw('count(*) as total'))
                            ->groupBy('categories.id', 'categories.name')
                            ->get();

        return response()->json($products);
    }
}
