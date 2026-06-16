<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Product;

#[Signature('app:product-info {id : ID del producto}')]
#[Description('Muestra información del producto')]
class ProductInfo extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');

        $product = Product::find($id);

        if (!$product) {
            $this->error("Producto con ID {$id} no encontrado.");
            return Command::FAILURE;
        }

        $this->info("Nombre: " . $product->name);
        $this->info("Precio: " . $product->price);
        $this->info("Descripción: " . $product->description);
    }
}
