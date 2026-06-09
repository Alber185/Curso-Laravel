<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function register(UserRequest $request)
    {
        $validatedData = $request->validated();
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        return response()->json(['message' => 'Usuario registrado exitosamente', 'user' => $user], Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();

        $credentials = [
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
        ];

        try{
            if(!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Credenciales inválidas'], Response::HTTP_UNAUTHORIZED);
            }
        }
        catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo crear el token'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json(['token' => $token]);
    }
/*
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Cierre de sesión exitoso'], Response::HTTP_OK);
    }
*/
}
