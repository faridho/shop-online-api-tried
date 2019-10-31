<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;
use DB;
use Carbon\Carbon;
use App\Helpers\ResponseHelper as RS;

class LoginController extends Controller
{
    public function register(request $request) {
      $validator = Validator::make($request->all(), [
        'name'     => 'required|string|max:50',
        'email'    => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
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

      $response = array(
        'message' => 'Register success',
        'token'   => $token,
        'data'    => $user
      );

      return response()->json(compact('response'));
    }

    public function login(Request $request) {
      $validator = Validator::make($request->all(), [
        'email'     => 'required|string|email|max:255',
        'password'  => 'required|string|max:255'
      ]);

      if($validator->fails()) {
        return response()->json($validator->errors(), 400);
      }

      $credentials = $request->only('email', 'password');

      try {
        if(!$token = JWTAuth::attempt($credentials)) {
          return response()->json([
            'message' => 'Invalid credentials'
          ], 400);
        }
      } catch (JWTException $th) {
        return response()->json([
          'message' => 'could not create token'
        ], 500);
      }

      $email    = $request->get('email');
      $dataUser = User::select('name', 'email')
                        ->where('email', $email)
                        ->get();
      
      $response = array(
        'message' => 'Login Success',
        'token'   => $token,
        'data'    => $dataUser
      );

      return response()->json(compact('response'), 200);
    }

    public function logout(Request $request) {
      $token = $request->header('Authorization');

      try {
        JWTAuth::invalidate($token);
        return response()->json([
          'message' => 'Logout Success'
        ], 200);
      } catch (JWTException $th) {
        return response()->json([
          'message' => 'Logout Failed'
        ], 500);
      }
    }

}
