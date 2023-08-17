<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'password' => 'required|string|min:6',
                'birthday' => 'required|date',
            ]);

            $validate_user = User::where('email', $request->email)->first();
            if ($validate_user) {
                $data = [
                    'status' => 'error',
                    'message' => 'User Registration Failed!',
                    'error' => 'Email already exists!',
                ];

                return response()->json($data, 409);
            }

            DB::beginTransaction();

            $user = new User();
            $user->name = $request->name;
            $user->birthday = $request->birthday;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();

            $token = $user->createToken('API')->plainTextToken;
            $user->remember_token = $token;
            $user->save();

            DB::commit();

            $data = [
                'status' => 'success',
                'user' => $user,
                'remember_token' => $token
            ];

            return response()->json($data, 201);
        } catch (\Exception $e) {
            DB::rollback();

            $data = [
                'status' => 'error',
                'line' => $e->getLine(),
                'error' => $e->getMessage(),
            ];

            return response()->json($data, 409);
        }
    }


    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('API')->plainTextToken;

                $data = [
                    'status' => 'success',
                    'user' => $user,
                    'remember_token' => $token
                ];

                return response()->json(['user' => $user, 'token' => $token], 200);
            } else {
                throw new ValidationException([], 'Credenciales inválidas', 401);
            }
        } catch (\Exception $e) {
            $data = [
                'status' => 'error',
                'line' => $e->getLine(),
                'error' => $e->getMessage(),
            ];

            return response()->json($data, 401);
        }
    }


    //Escuchar, aprendiendo a negociar cap 33. Salió mal


}
