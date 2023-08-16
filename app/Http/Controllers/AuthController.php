<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request) {
        
      
        $credenciais = $request->all(['email', 'password']);   
        $token = auth('api')->attempt($credenciais);    

        if($token) {   //user autenticado com sucesso
            return response()->json(['token' => $token]);
        } else {  //erro de user ou senha
            return response()->json(['erro' => 'Usuario ou senha invalidos'], 403);     
        }
        
    }

    public function logout() {
        auth('api')->logout();
        return response()->json(['msg' => 'O logout foi realizado com sucesso']);
    }

    public function refresh() {
        $token = auth('api')->refresh();
        return response()->json(['token' => $token]);
    }

    public function me() {
        return response()->json(['user' => auth('api')->user()]);
    }
}
