<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(AuthRegisterRequest $request){
        try{
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            $accessToken = $user->createToken($user->name.'-AuthToken');

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $accessToken->plainTextToken,
            ], Response::HTTP_OK);

        }catch(\Throwable $th) {
            Log::error($th);
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function login(AuthLoginRequest $request)
    {
        try {

            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], Response::HTTP_BAD_REQUEST);
            }
            
            $accessToken = $user->createToken($user->name.'-AuthToken',['*'],Carbon::now()->addMinutes(config('sanctum.expiration')));

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $accessToken->plainTextToken,
                'user' => $user,
            ], Response::HTTP_OK);

        }catch(\Throwable $th) {
            Log::error($th);
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function logout(Request $request){

        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => true,
            'message' => 'User Logged Out Successfully',
        ], Response::HTTP_OK);
    }
}
