<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class products_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories_ids = DB::table("categories")->pluck("id");

        if($categories_ids->isEmpty()) {
            $this->command->info("No hay categorías para asignar.");
            return;
        }

        // Faker es una biblioteca que nos permite generar datos de prueba de manera sencilla.
        // En este caso, la utilizamos para generar nombres y descripciones aleatorias para nuestros productos.
        $faker = Faker::create();

        for ($i = 1; $i <= 20; $i++) {
            DB::table("products")->insert([
                "name" => $faker->word,
                "description" => $faker->sentence,
                "category_id" => $categories_ids->random(),
                "created_at" => now(),
                "updated_at" => now(),
            ]);
        }
    }
}
