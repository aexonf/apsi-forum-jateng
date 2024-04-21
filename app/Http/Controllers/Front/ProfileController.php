<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Supervisors;
use Illuminate\Http\Request;

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
        // TODO: FIX request does'not have file
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
                'label' => $request->label,
                'img_url' => $nameImage,
            ]);
            $supervisor->save();

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
