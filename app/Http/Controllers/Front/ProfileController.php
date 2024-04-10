<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index() {
        return response()->json([
            "status" => "success",
            "message" => "Success show profile",
            "data" => auth()->user()->supervisor
        ],200);
    }



    // public function update(Request $request){
    //     $user =
    // }


}
