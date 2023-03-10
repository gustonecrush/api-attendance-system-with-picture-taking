<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * register
     *
     * @param  mixed $request
     * @return void
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' =>
                'required|string|min:8|confirmed|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($user) {
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(
                [
                    'data' => $user,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ],
                200
            );
        } else {
            return response()->json(['message' => 'Register is Failed'], 400);
        }
    }

    /**
     * login
     *
     * @param  mixed $request
     * @return void
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' =>
                'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => "Hi $user->name welcome to home",
            'is_admin' => $user->is_admin,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * logout
     *
     * @return void
     */
    public function logout()
    {
        Auth::user()
            ->tokens()
            ->delete();

        return response()->json(['message' => 'Logout successfully'], 200);
    }
}
