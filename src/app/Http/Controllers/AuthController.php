<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function create(Request $request)
    {    
      
        // valido
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed', // confirmacion de pass
        ]);  

        // creo un usuario
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // Hash
        ]);

        // api token
        $token = $user->createToken('Personal Access Token')->plainTextToken;

        //regreso el token y un 201
        return response()->json(['token' => $token,], 201);
              
    }

    //------------------------------------------------------------

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        // compruebo el user y pass con Hash::check
        if (!$user || !Hash::check($request->password, $user->password)) {

            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas.'],
            ]);

        }

        // obtengo el token
        $token = $user->createToken('Personal Access Token')->plainTextToken;

        //regreso el token y un 200 (ok)
        return response()->json(['token' => $token,], 200);
    }

    //----------------------------------------------------

    public function revokeToken(Request $request)
    {

        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // traigo usuario autenticado
        $user = User::where('email', $request->email)->first();

         // compruebo el user y pass con Hash::check
         if (!$user || !Hash::check($request->password, $user->password)) {

            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas.'],
            ]);

        }

        $token = $user->tokens()->delete(); 

        if (!$token) {

            return response()->json(['message' => 'no hay token para eliminar.'], 404);
        }


        return response()->json(['message' => 'el token fue destruido.'], 200);
    }

}
