<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Supervisors;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index() {
        $user = auth()->user();
        $supervisor = $user->supervisor;
        $data = [
            'name' => $supervisor->name,
            'email' => $supervisor->email,
            'label' => $supervisor->label,
            'level' => $supervisor->level,
        ];
        return response()->json([
            "status" => "success",
            "message" => "Success show profile",
            "data" => $data
        ],200);
    }



    public function update(Request $request){
        try {

            $supervisor = Supervisors::where("user_id", auth()->user()->id)->first();
            $nameImage = null;

            if ($request->hasFile('image')) {
                $image = $request->file('image');

                $imageName = 'image-' . time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public', $imageName);
                $nameImage = $imageName;
            }

            $supervisor->update([
                "email" => $request->email,
                "phone_number" => $request->phone_number,
                "label" => $request->label,
                "img_url" => $nameImage
            ]);

            return response()->json([
                "status" => "success",
                "message" => "Success update profile",
                "data" => $supervisor
            ],200);


        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Update profile failed",
                "data" => null
            ],500);
        }

    }


}
