<?php

namespace App\Http\Controllers;
use App\User;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class PassportController extends Controller
{
    //
  public function login(Request $request) {
    $response = [];
    $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials)) {
      $user = Auth::user();
      $response['success'] = true;
      $response['token'] = $user->createToken('MyApp')->accessToken;
      $response['user'] = $user;
      return response()->json($response, 200);
      
    } else {
      
      $response['success'] = false;
      $response['msg'] = 'Usuario o contraseña incorrectos';
      return response()->json($response, 401);
    }
  }
  
  public function register(Request $request) {
    $response = [];
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'email' => 'required|email',
      'password' => 'required',
      'password_confirmation' => 'required|same:password'
    ]);
    if($validator->fails()) {
      return response()->json(['$error'=>$validator->errors()], 401);
    }
    $user = User::create([
      'name' => $request->input('name'),
      'email' => $request->input('email'),
      'password' => Hash::make($request->input('password')),
    ]);
    $response['success'] = true;
    $response['token'] = $user->createToken('MyApp')->accessToken;
    $response['name'] = $user->name;
    return response()->json($response, 200);

  }
}
