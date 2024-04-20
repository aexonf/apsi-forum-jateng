<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    public function index(Request $request)
    {
        $data = null;

        if ($request->sort == "newest") {
            $data = Publication::orderBy('created_at', 'desc')->get();
        } elseif ($request->sort == "oldest") {
            $data = Publication::orderBy('created_at', 'asc')->get();
        } else {
            $data = Publication::all();
        }

        try {
            return response()->json([
                "status" => "success",
                "message" => "Success show publication",
                "data" => $data,
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
