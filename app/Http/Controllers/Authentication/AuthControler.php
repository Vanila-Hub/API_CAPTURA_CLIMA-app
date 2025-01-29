<?php

namespace App\Http\Controllers\Authentication;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

class AuthControler extends Controller
{
    public function login(Request $request) 
    {
        // Validación de los datos
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        // Obtener el usuario por su nombre
        $user = User::where('name', $request->name)->first();

        // Verificar que el usuario exista y que la contraseña coincida
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Unauthorized password not matching my Hash'
            ], 401);
        }

        // Crear el token
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ], 200);
    }

    public function signUp(Request $request)
    {
        // Update validation rules to include email
        $request->validate([
            'name' => 'required|string|unique:users',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ]);

        // Include email in the user creation
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Successfully created user, now login to get the token.'
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user(), 200);
    }
}
