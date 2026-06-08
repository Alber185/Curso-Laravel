<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BackendController;
use App\Http\Controllers\QueriesController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\CheckValueInHeader;
use App\Http\Middleware\LogRequests;
use App\Http\Middleware\UpperCaseName;

Route::get("/test", function() { return "El backend funciona correctamente"; })
    ->middleware(LogRequests::class);

Route::get("/backend", [BackendController::class, "getAllNames"]);
Route::get("/backend/{id}", [BackendController::class, "get"]);
Route::post("/backend", [BackendController::class, "create"]);
Route::put("/backend/{id}", [BackendController::class, "update"]);
Route::delete("/backend/{id}", [BackendController::class, "delete"]);

// Pruebas de Eloquent (ORM de Laravel)
Route::get("/queries", [QueriesController::class, "get"]);
Route::get("/queries/names", [QueriesController::class, "getNames"]);
Route::get("/queries/{id}", [QueriesController::class, "getById"]);
Route::get("/queries/filter/{name}/{category}", [QueriesController::class, "filterNamesAndCategory"]);
Route::post("/queries/search", [QueriesController::class, "advancedSeach"]);
Route::get("/queries/method/join", [QueriesController::class, "join"]);
Route::get("/queries/method/group", [QueriesController::class, "groupBy"]);

// Información de productos
Route::get("/products", [ProductController::class, "index"]);
Route::post("/storeproduct", [ProductController::class, "store"])
    ->middleware([CheckValueInHeader::class, UpperCaseName::class]);
Route::put("/updateproduct/{id}", [ProductController::class, "update"]);
Route::delete("/product/{id}", [ProductController::class, "delete"])
    ->middleware(CheckValueInHeader::class);