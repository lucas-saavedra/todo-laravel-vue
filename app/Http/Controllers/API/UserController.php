<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;



class UserController extends Controller
{

    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            "email" => 'required | email |unique:users',
            'password' => 'required | confirmed'
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $user->save();
            auth()->login($user);
            $token =  $user->createToken("auth_token")->plainTextToken;
            DB::commit();

            return response()->json([
                "status" => true,
                "message" => "!Welcome! " . $user->name,
                "data" => [
                    "access_token" => $token,
                    "user" => $user,
                ]
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response($e->getMessage());
        }
    }
    public
    function login(Request $request)
    {
        $request->validate([
            "email" => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', "=", $request->email)->first();

        if (isset($user->id)) {

            if (Hash::check($request->password, $user->password)) {
                $token =  $user->createToken("auth_token")->plainTextToken;

                return response()->json([
                    "status" => true,
                    "message" => "!Welcome! " . $user->name,
                    "data" => [
                        "access_token" => $token,
                        "user" => $user,
                    ]
                ], 200);
            } else {
                return response()->json([
                    "status" => 0,
                    "message" => "Invalid Password.",
                    "data" => [],
                ], 404);
            };
        } else {
            return response()->json([
                "status" => false,
                "message" => "The user with that email does not exists.",
                "data" => []
            ], 404);
        }
    }

    public
    function profile()
    {
        return response()->json([
            "status" => true,
            "message" => "Profile information",
            "data" =>  [
                "user" => auth()->user()
            ]
        ]);
    }

    public
    function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            "status" => true,
            "message" => "Logged out.",
            "data" => []

        ]);
    }
}
