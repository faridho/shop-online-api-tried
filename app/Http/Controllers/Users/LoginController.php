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
        $response = array(
          'status'  => false,
          'message'   => $validator->errors()->all()
        );
        return response()->json(compact('response'));
      }

      $user = User::create([
        'name'     => $request->get('name'),
        'email'    => $request->get('email'),
        'password' => Hash::make($request->get('password'))
      ]);

      $token = JWTAuth::fromUser($user);

      $response = array(
        'status'  => true,
        'message' => 'Register success. Please login using your username and password',
        'token'   => $token,
        'data'    => $user
      );

      return response()->json(compact('response'));
    }

    public function login(Request $request) {
      $validator = Validator::make($request->all(), [
        'email'     => 'required',
        'password'  => 'required'
      ]);

      if($validator->fails()) {
        return response()->json($validator->errors()->all());
      }

      $credentials = $request->only('email', 'password');

      try {
        if(!$token = JWTAuth::attempt($credentials)) {
          $response = array (
            'status' => false,
            'message' => 'Invalid Credentials'
          );

          return response()->json(compact('response'));
        }
      } catch (JWTException $th) {
        $response = array (
          'status' => false,
          'message' => 'Could not create token'
        );

        return response()->json(compact('response'));
      }

      $email    = $request->get('email');
      $dataUser = User::select('name', 'email')
                        ->where('email', $email)
                        ->first();
      
      $response = array(
        'status'  => true,
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
