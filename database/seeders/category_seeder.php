<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class category_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("category")->insert([
            ["nombre" => "Electrónica"],
            ["nombre" => "Ropa"],
            ["nombre" => "Hogar"],
            ["nombre" => "Juguetes"],
            ["nombre" => "Libros"],
        ]);
    }
}
