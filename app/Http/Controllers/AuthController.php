<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function funIngresar(Request $request){
        // validar 
        $credenciales = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
        //autenticar
        if(!Auth::attempt($credenciales)){
            return response()->json(["mensaje" => "credenciales incorrectas"], 401);
        }
        // generar token 
        $token = $request->user()->createToken('Token Auth')->plainTextToken;

        return response()->json([
            "access_token" => $token,
            "usuario" => $request->user()
        ],201);
    }


    public function funRegistro(Request $request){
        // validar 
        $request->validate([
            "name" => "required",
            "email" => "required|email",
            "password" => "required|same:cpassword"
        ]);
        // guardar
        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password); //encripta contraseña

        $usuario->save();

        return response()->json(["mensaje" => "usuario Registrado"], 201);

    }
    public function funPerfil(Request $request){
        // procesar
        return response()->json($request->user(), 201);
    }
    public function funSalir(Request $request){
        // procesar
        $request->user()->tokens()->delete();
        return response()->json(["mensaje"=>"Salio"], 201);
    }
}
