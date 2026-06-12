<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Events\UserRegistered;

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

        event(new UserRegistered($user));

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
        return $this->respondWithToken($token);
    }

    public function who()
    {
        $user = JWTAuth::user();
        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], Response::HTTP_UNAUTHORIZED);
        }
        return response()->json(['user' => $user], Response::HTTP_OK);
    }

    public function logout()
    {
        try{
            $token = JWTAuth::getToken();
            JWTAuth::invalidate($token);
            return response()->json(['message' => 'Cierre de sesión exitoso'], Response::HTTP_OK);
        }
        catch (JWTException $e) {
            return response()->json(['error' => 'No se pudo cerrar la sesión o el token no es válido'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function refresh()
    {
        try {
            $token = JWTAuth::getToken();
            $newToken = JWTAuth::refresh($token);
            $newToken = $this->respondWithToken($newToken);
            JWTAuth::invalidate($token);
            return $newToken;
        } catch (JWTException $e) {
            return response()->json(['error' => 'No se pudo refrescar el token'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //region - Configuración de autenticación
    protected function respondWithToken(string $token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL()
        ]);
    }
    //endregion
}
