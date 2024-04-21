<?php

namespace App\Http\Controllers;

use App\Models\Supervisors;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller
{


    public function login(Request $request)
    {
        $request->validate([
            "username" => "required",
            "password" => "required",
        ]);
        $supervisor = Supervisors::where("id_number", $request->username)->first();

        if ($supervisor) {
            if (!Hash::check($request->password, $supervisor->user->password)) {
                return redirect()->back()->with("error", "Username atau password salah");
            }

            $token = $supervisor->user->createToken($supervisor->user->username)->plainTextToken;


            return Inertia::render('login', [
                "token" => $token,
                "is_password_change" => $supervisor->user->is_password_change,
            ]);
        }

        $user = User::where("username", $request->username)->first();

        if ($user) {
            if (!Hash::check($request->password, $user->password)) {
                return redirect()->back()->with("error", "Username atau Password Anda salah");
            }

            if (Auth::attempt($request->only("username", "password"))) {
                return redirect("/dashboard")->with("success", "Login berhasil");
            }
        }

        return redirect()->back()->with("error", "Gagal melakukan autentikasi");
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

    public function logout()
    {
        if (auth()->user()->role !== 'supervisor') {
            Auth::logout();

            return redirect()->route('login');
        } else {
            auth()->user()->tokens()->delete();

            return response()->json([
                "status" => "success",
                "message" => "Logout Success",
                "data" => null
            ]);
        }
    }
}
