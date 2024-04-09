<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\DiscussionComments;
use Illuminate\Http\Request;

class DiscussionCommentController extends Controller
{

    public function create($id, Request $request)
    {
        // Validate incoming request data
        $request->validate([
            "content" => "required",
        ]);

        try {
            // Prepare updated discussion data
            $discussionComment = DiscussionComments::create([
                "discussion_id" => $id,
                "supervisor_id" => auth()->user()->supervisor->id,
                "content" => $request->content
            ]);

            return response()->json([
                "status" => "success",
                "message" => "Discussion comments updated successfully",
                "data" => $discussionComment
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "Discussion comments updated failed",
                "data" => null
            ], 200);
        }
    }

    public function update($id, Request $request)
    {
        // Validate incoming request data
        $request->validate([
            "content" => "required",
        ]);

        try {
            // Prepare updated discussion data
            $discussionComment = DiscussionComments::find($id);
            $discussionComment->content = $request->content;
            $discussionComment->save();

            return response()->json([
                "status" => "success",
                "message" => "Discussion comments updated successfully",
                "data" => $discussionComment
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "Discussion comments updated failed",
                "data" => null
            ], 200);
        }
    }

    public function destroy($id) {
        try {
            $discussionComment = DiscussionComments::find($id);
            $discussionComment->delete();

            return response()->json([
                "status" => "success",
                "message" => "Discussion comments deleted successfully",
                "data" => $discussionComment
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "Discussion comments deleted failed",
                "data" => null
            ], 200);
        }
    }


    public function like($id)
    {
        try {
            // Check if the user has already liked the discussion
            $likeFind = DiscussionComments::where("discussion_comments_id", $id)->where("supervisor_id", auth()->user()->supervisor->id)->first();

            // If user has already liked the discussion, unlike it
            if ($likeFind) {
                $likeFind->delete();
            }

            // Like the discussion
            $discussionLike =  $likeFind->create([
                "discussions_id" => $id,
                "supervisor_id" => auth()->user()->supervisor->id
            ]);

            // Check if discussion like was successful
            if ($discussionLike) {
                return response()->json([
                    "status" => "success",
                    "message" => "Discussion liked successfully",
                    "data" => $discussionLike
                ]);
            }

            // Return error response if discussion like failed
            return response(500)->json([
                "status" => "error",
                "message" => "Discussion like failed",
                "data" => null
            ]);
        } catch (\Throwable $th) {
            // Return error response if an exception occurred during discussion like
            return response(500)->json([
                "status" => "error",
                "message" => "Discussion like failed",
                "data" => null
            ]);
        }
    }

}
