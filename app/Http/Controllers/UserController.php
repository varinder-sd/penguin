<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(Request $request){
		
		try {
			
			$request->validate([
			  'email' => 'email|required',
			  'password' => 'required',
			  'device_name' => 'required',
			]);
			$credentials = request(['email', 'password']);
			if (!Auth::attempt($credentials)) {
			  return response()->json([
				'status_code' => 500,
				'message' => 'The provided credentials are incorrect.'
			  ]);
			}
			$user = User::where('email', $request->email)->first();
			
			if ( ! Hash::check($request->password, $user->password, [])) {
			   throw new \Exception('Wrong Password');
			   // throw ValidationException::withMessages([
				// 'email' => ['The provided credentials are incorrect.'],
				// ]);
			}
			//$user->createToken($request->device_name)->plainTextToken;
			$tokenResult = $user->createToken('authToken')->plainTextToken;
			return response()->json([
			  'status_code' => 200,
			  'access_token' => $tokenResult,
			  'token_type' => 'Bearer',
			]);	
		}catch (Exception $error){
			die('here');
			return response()->json([
			  'status_code' => 500,
			  'message' => 'Error in Login',
			  'error' => $error,
			]);
		}
	}
}
