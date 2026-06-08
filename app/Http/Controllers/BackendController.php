<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;

class BackendController extends Controller
{
    private $names = [
        1 => ['name' => 'Juan', 'age' => 30],
        2 => ['name' => 'María', 'age' => 25],
        3 => ['name' => 'Pedro', 'age' => 35],
    ];



    //FUNCIÓN GET ALL
    function getAllNames() {
        return response()->json($this->names);
    }


    // FUNCIÓN GET
    public function get(int $id = 0) {
        if (!isset($this->names[$id])) {
            return response()->json([
                "success" => false,
                "message" => "No existe el nombre con id $id"
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            "success" => true,
            "message" => "Hola " . $this->names[$id]['name']
        ]);
    }


    // FUNCIÓN POST
    public function create(Request $request){
        //Comprobamos que están todos los datos
        if (!$request->has("name") || !$request->has("age")) {
            return response()->json([
                "success" => false,
                "message" => "Faltan datos para crear la persona"
            ], Response::HTTP_BAD_REQUEST);
        }

        // Como los datos existen se crea una entrada de persona
        $person = [
            "id" => count($this->names) + 1,
            "name" => $request->input("name"),
            "age" => $request->input("age")
        ];

        // Se añaden los datos al nuevo item de la lista de personas
        $this->names[$person["id"]] = $person;

        // Se devuelve la respuesta con el nuevo item creado
        return response()->json([
            "success" => true,
            "message" => "Persona creada correctamente",
            "data" => $person
        ], Response::HTTP_CREATED);
    }


    // FUNCIÓN PUT
    public function update(int $id, Request $request) {
        // Comprobamos que el id existe
        if (!isset($this->names[$id])) {
            return response()->json([
                "success" => false,
                "message" => "No existe el nombre con id $id"
            ], Response::HTTP_NOT_FOUND);
        }

        // Comprobamos que están todos los datos
        if (!$request->has("name") || !$request->has("age")) {
            return response()->json([
                "success" => false,
                "message" => "Faltan datos para actualizar la persona"
            ], Response::HTTP_BAD_REQUEST);
        }

        // Se actualizan los datos de la persona
        $this->names[$id]['name'] = $request->input("name");
        $this->names[$id]['age'] = $request->input("age");

        // Se devuelve la respuesta con el item actualizado
        return response()->json([
            "success" => true,
            "message" => "Persona actualizada correctamente",
            "data" => $this->names[$id]
        ]);
    }

    // FUNCIÓN DELETE
    public function delete(int $id) {
        // Comprobamos que el id existe
        if (!isset($this->names[$id])) {
            return response()->json([
                "success" => false,
                "message" => "No existe el nombre con id $id"
            ], Response::HTTP_NOT_FOUND);
        }

        // Se elimina la persona
        unset($this->names[$id]);

        // Se devuelve la respuesta con el item eliminado
        return response()->json([
            "success" => true,
            "message" => "Persona $id eliminada correctamente"
        ]);
    }
}