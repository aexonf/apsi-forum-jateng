<?php

namespace App\Http\Controllers;

use App\Models\Supervisors;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{


    public function login(Request $request)
    {
        $request->validate([
            "password" => "required"
        ]);

        if ($request->has("id_number")) {
            $user = Supervisors::with("user")->where("id_number", $request->id_number)->first();

            if (!$user) {
                return response()->json([
                    "status" => "error",
                    "message" => "User with the provided ID number not found",
                    "data" => null
                ]);
            }

            $passwordCheck = User::where("id", $user->user_id)->first();

            if (Hash::check($request->password, $passwordCheck->password)) {
                $token = $passwordCheck->createToken($passwordCheck->username)->plainTextToken;

                if (!$passwordCheck->is_password_change) {
                    return response(301)->json([
                        "status" => "reset-password",
                        "message" => "Password belum di reset",
                        "token" => $token,
                        "data" => null,
                    ]);
                }


                return response()->json([
                    "status" => "success",
                    "message" => "Login Success",
                    "token" => $token,
                    "data" => $user
                ]);
            }

            return response()->json([
                "status" => "error",
                "message" => "Login error",
                "data" => null
            ]);
        }

        $user = User::where("username", $request->username)->first();

        if (!$user) {
            return response()->json([
                "status" => "error",
                "message" => "User with the provided username not found",
                "data" => null
            ]);
        }
        $token = $user->createToken($user->username)->plainTextToken;

        if (Hash::check($request->password, $user->password)) {
            if (!$user->is_password_change) {
                return response()->json([
                    "status" => "error",
                    "message" => "Password belum di reset",
                    "data" => null,
                    "token" => $token
                ]);
            }


            return response()->json([
                "status" => "success",
                "message" => "Login Success",
                "token" => $token,
                "data" => $user,
            ]);
        }

        return response()->json([
            "status" => "error",
            "message" => "Login error",
            "data" => null
        ]);
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            "password" => "required"
        ]);

        try {

            $user = User::where("id", auth()->user()->id)->update([
                "password" => Hash::make($request->password),
                "is_password_change" => true
            ]);

            if ($user) {
                return response()->json([
                    "status" => "success",
                    "message" => "password berhasil di reset",
                    "data" => null
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "password gagal di reset",
                "data" => $th->getMessage()
            ]);
        }
    }


}
