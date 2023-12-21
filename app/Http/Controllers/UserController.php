<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }

            if(!empty($token)){
                $user = Auth::user();
                $token = JWTAuth::fromUser($user);
                session()->put('token', $token);

                return redirect()->route('dashboard');
            }


        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        //Redirigir a vista con credenciales correctas.
        return response()->json(compact('token'));
    }
    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        return response()->json(compact('user'));
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
            ]);

            $token = JWTAuth::fromUser($user);
            //Redireccionar  a vista
            return response()->json(compact('user', 'token'), 201);
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function logout(Request $request)
    {
        $token = session()->get('token');
        try {
            JWTAuth::invalidate($token);
            return redirect()->route('login');

        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error: '. $exception->getMessage()
            ], 500);
        }
    }
}
