<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
  /**
   * Register a new user.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:6|confirmed',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 400);
    }

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => bcrypt($request->password),
    ]);

    $token = JWTAuth::fromUser($user);

    return response()->json(compact('user', 'token'), 201);
  }

  /**
   * Control user and return a user.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function control(Request $request)
  {
    $data = $request->json()->all();
    $user = User::where('email', $data["email"])->first();
    if (Hash::check($data["password"], $user->password)) {
      return response()->json($user);
    } else {
      return response()->json(['error' => 'user not found'], 401);
    }
    return response()->json($user);
  }

  /**
   * Login user and return a token.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function login(Request $request)
  {
    $credentials = $request->json()->all();

    if (!isset($credentials['email']) || !isset($credentials['password'])) {
      return response()->json(['error' => 'Email and password are required'], 400);
    }

    if (!$token = JWTAuth::attempt($credentials)) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    $user = User::where('email', $credentials['email'])->first();
    if (Hash::check($credentials["password"], $user->password)) {
      $user->api_token = $token;
      $user->save();

      $cookie = Cookie::make('token', $token, 60 * 24 * 365);
      return response()->json(compact('user', 'token'))->withCookie($cookie);
    } else {
      return response()->json(['error' => 'user not found'], 401);
    }
  }

  /**
   * Get the authenticated User.
   *
   * @return \Illuminate\Http\Response
   */
  public function user()
  {
    return response()->json(auth()->user());
  }

  /**
   * Logout the user (Invalidate the token).
   *
   * @return \Illuminate\Http\Response
   */
  public function logout()
  {
    $user = User::where('email', auth()->user()->email)->first();
    $user->api_token = "";
    $user->save();

    auth()->logout();

    return response()->json(['message' => 'Successfully logged out']);
  }

  /**
   * Refresh a token.
   *
   * @return \Illuminate\Http\Response
   */
  public function refresh()
  {
    return response()->json(auth()->refresh());
  }
}
