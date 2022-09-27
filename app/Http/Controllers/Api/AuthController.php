<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\ApiResponseTrait;
use App\Models\User;
use Validator;

class AuthController extends Controller
{

    use ApiResponseTrait;
    
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }
        $credentials = $request->only('email', 'password');

        if (! $token = auth()->attempt($validator->validated())) {
            return $this->errorResponse('invalid credentails', 401, 'unauthenticated');
        }

        $user = Auth::user();
        $user->token = $token;
        
        return $this->successResponse($user);

    }

    public function register(Request $request){
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if($validator->fails()) {
            return $this->errorResponse($validator->messages(), 400);
        }
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);
        $user->token = $token;
        return $this->successResponse($user);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }
}
