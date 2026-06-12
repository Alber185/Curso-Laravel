<?php

namespace App\Http\Controllers;

use App\Business\Services\ProductService;
use App\Models\Product;
use App\Business\Interfaces\MessageServiceInterface;
use App\Business\Services\EncryptService;

class InfoController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected EncryptService $encryptService)
    {

    }

    public function hiMessage(MessageServiceInterface $hiService)
    {
        return response()->json([$hiService->hi()]);
    }

    public function priceWithIVA(float $id)
    {
        $product = Product::findOrFail($id);
        if(!$product)
            return response()->json(['message' => 'Producto no encontrado']);
        
        $priceWithIVA = $this->productService->calcularPrecioConIVA($product->price);

        return response()->json([
            'product' => $product->name,
            'price' => $product->price,
            'price_with_iva' => $priceWithIVA
        ]);
    }

    public function encrypt(string $data)
    {
        return response()->json($this->encryptService->encrypt($data));
    }

    public function decrypt(string $data)
    {
        return response()->json($this->encryptService->decrypt($data));
    }
}
