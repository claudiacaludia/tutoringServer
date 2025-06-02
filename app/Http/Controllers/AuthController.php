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
            return response()->json(['error' => 'Unauthorized'], 401); // wenn das nicht klappt, geben wir einen Fehler zurück
        }

        return $this->respondWithToken($token);
    }

    public function logout(){
        auth()->logout();
        return response()->json(['message'=> 'Successfully logged out']);
    }

    //Damit der Client nicht jede Stunde ausgeloggt wird sondern immer wenn das geändert wird token refreshed wird
    public function refresh(){
        return $this->respondWithToken(auth()->refresh());
    }

    public function getUserRole(int $id): JsonResponse {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json($user->role);
    }




    protected function respondWithToken($token) // wir geben den token zurück
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}

