<?php

namespace App\Http\Controllers\Tries;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;
use DB;

class TriesController extends Controller
{
    public function registerToken(Request $request) {
      $validator = Validator::make($request->all(), [
        'name'     => 'required|string|max:255',
        'email'    => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed'
      ]);

      if($validator->fails()) {
        return response()->json($validator->errors(), 400);
      }

      $user = User::create([
        'name'     => $request->get('name'),
        'email'    => $request->get('email'),
        'password' => Hash::make($request->get('password'))
      ]);

      $token = JWTAuth::fromUser($user);

      return response()->json(compact('user', 'token') ,201);
    }

    public function generateToken(Request $request) {
      $credentials = $request->only('email', 'password');

      try {
        if(!$token = JWTAuth::attempt($credentials)) {
          return response()->json([
            'error' => 'Invalid credentials'
          ], 400);
        }
      } catch (JWTException $th) {
        return response()->json([
          'error' => 'could not create token'
        ], 500);
      }

      return response()->json(compact('token'), 200);
    }

    public function accessToken() {
      $response = 'Only authorized users can see';
      return response()->json(compact('response'), 200);
    }

    public function accessUsers() {
      $users = DB::select("CALL users_getAll()");
      return response()->json(compact('users'), 200);
    }
}


