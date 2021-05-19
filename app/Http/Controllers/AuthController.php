<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller {


    /**
     * Login guru
     *
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {
    
        try {

            $messages = [
                'required' => ':attribute tidak boleh kosong'
            ];

            $attributes = [
                'email' => 'Email',
                'password' => 'Password'
            ];

            $validator = Validator::make($request->all(), [
                'email' => 'required|string',
                'password' => 'required|string',
            ], $messages, $attributes);

            if($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => 'The given data was invalid.',
                    'errors' => $validator->errors()
                ], 400);
            }

            if(!$token = JWTAuth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Login failed.',
                    'errors' => [
                        'email' => ['Email atau password salah']
                    ]
                ], 400);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Login success.',
                'data' => [
                    'expires_in' => JWTAuth::factory()->getTTL() * 60000,
                    'token' => $token
                ]
            ]);
        } catch (Exception $e) {
            return $this->errorMessage($e);
        }

    }

    /**
     * Logout authenticated user
     *
     * @return void
     */
    public function logout() {

        try {
            JWTAuth::parseToken()->invalidate();

            return response()->json([
                'status' => 200,
                'message' => 'Logout success.'
            ]);
        } catch (Exception $e) {
            return $this->errorMessage($e);
        }

    }

    /**
     * Get authenticated user data
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function getUser() {
        
        try {

            $user = auth()->user();

            return response()->json([
                'status' => 200,
                'message' => 'Fetch success.',
                'data' => $user
            ]);

        } catch(Exception $e) {
            return $this->errorMessage($e);
        }

    }
    
}
