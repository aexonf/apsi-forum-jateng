<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    public function index()
    {
        try {
            return response()->json([
                "status" => "success",
                "message" => "Success show publication",
                "data" => Publication::all()
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Failed to get publication",
                "data" => $th->getMessage(),
            ], 500);
        }
    }


    public function download($id)
    {
        try {

            $publication = Publication::find($id);
            $publication->download_count += 1;
            $update = $publication->save();


            if ($update) {
                return response()->json([
                    "status" => "success",
                    "message" => "Success download publication",
                    "data" => $publication
                ]);
            }

            return response()->json([
                "status" => "error",
                "message" => "Failed to download publication",
                "data" => null,
            ], 500);
        } catch (\Throwable $th) {

            return response()->json([
                "status" => "error",
                "message" => "Failed to download publication",
                "data" => $th->getMessage(),
            ], 500);
        }
    }
}
