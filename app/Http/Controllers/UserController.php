<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(AuthRequest $request){
        // On récupère les données validées de la requête
  $credentials = $request->only('email', 'password');

  if (Auth::attempt($credentials)) {
      echo('vtii' );
      echo($request->email);
      $token = $request->user()->createToken('auth_token');
      return ['token' => $token->plainTextToken ,];
      //return response()->json(['message' => 'Authenticated']);
  }

  // Si l'authentification échoue
  return response()->json(['message' => 'Invalid credentials'], 401);

  }
}
