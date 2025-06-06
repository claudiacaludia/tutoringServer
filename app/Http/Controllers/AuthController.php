<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['login', 'getUserRole']]); // alle Methoden außer login müssen authentifiziert werden
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password'); // wir holen uns die credentials aus dem request

        if (!$token = auth()->attempt($credentials)) { // wir versuchen, ein token zu erstellen
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function logout(){
        auth()->logout();
        return response()->json(['message'=> 'Successfully logged out']);
    }

    public function refresh(){
        return $this->respondWithToken(auth()->refresh());
    }



    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}

