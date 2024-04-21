<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Supervisors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $supervisor = $user->supervisor;

        return response()->json([
            "status" => "success",
            "message" => "Success show profile",
            "data" => $supervisor
        ], 200);
    }



    public function update(Request $request)
    {
        try {
            $supervisor = Supervisors::where("user_id", auth()->user()->id)->first();
            $nameImage = $supervisor->img_url;

            if ($request->hasFile('image')) {
                $image = $request->file('image');

                $imageName = 'image-' . time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public', $imageName);
                $nameImage = $imageName;
                Storage::delete(
                    "/public/" . $supervisor->img_url
                );
            }

            $data = [
                'label' => $request->label ? $request->label : $supervisor->label,
                'img_url' => $nameImage,
                'email' => $request->email ? $request->email : $supervisor->email,
                'phone_number' => $request->phone_number ? $request->phone_number : $supervisor->phone_number,
            ];



            $supervisor->update($data);

            return response()->json([
                "status" => "success",
                "message" => "Success update profile",
                "data" => $supervisor
            ], 200);


        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Update profile failed",
                "data" => null,
                "error" => $th->getMessage()
            ], 500);
        }

    }


}
