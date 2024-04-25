<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\DiscussionCommentLikes;
use App\Models\DiscussionComments;
use App\Models\Discussions;
use Illuminate\Http\Request;

class DiscussionCommentController extends Controller
{

    public function detail($id)
    {
        $discussionComments = DiscussionComments::with(["discussions", "supervisors"])->where('discussion_id', $id)->get();
        $likedComment = [];
        foreach ($discussionComments as $comment) {
            $likedComment[] = DiscussionCommentLikes::where('discussion_comments_id', $comment->id)->get();
        }

        if (!$discussionComments) {
            return response()->json([
                "status" => "error",
                "message" => "Discussion comments not found"
            ], 404);
        }

        return response()->json([
            "status" => "success",
            "message" => "Discussion comments retrieved successfully",
            "data" => $discussionComments,
            "commentLike" => $likedComment,
            "user" => auth()->guard("sanctum")->user()->supervisor
        ], 200);
    }

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
                "message" => "Discussion comments created successfully",
                "data" => $discussionComment
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "Discussion comments creation failed",
                "data" => null
            ], 500);
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
                "message" => "Discussion comments update failed",
                "data" => null
            ], 500);
        }
    }

    public function destroy($id)
    {
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
                "message" => "Discussion comments deletion failed",
                "data" => null
            ], 500);
        }
    }

    public function like($id)
    {
        try {
            // Check if the user has already liked the discussion comment
            $likeFind = DiscussionCommentLikes::where('discussion_comments_id', $id)->where('supervisor_id', auth()->user()->supervisor->id)->first();
            // If user has already liked the discussion comment, unlike it
            if ($likeFind) {
                $likeFind->delete();
                return response()->json([
                    "status" => "success",
                    "message" => "Discussion comment unlike successfully",
                    "data" => null
                ], 200);
            } else {
                // Like the discussion comment
                $discussionLike = DiscussionCommentLikes::create([
                    "discussion_comments_id" => $id,
                    "supervisor_id" => auth()->user()->supervisor->id
                ]);

                // Check if discussion comment like was successful
                if ($discussionLike) {
                    return response()->json([
                        "status" => "success",
                        "message" => "Discussion comment liked successfully",
                        "data" => $discussionLike
                    ], 200);
                }
            }


            // Return error response if discussion comment like failed
            return response()->json([
                "status" => "error",
                "message" => "Discussion comment like failed",
                "data" => null,
            ], 500);
        } catch (\Throwable $th) {
            // Return error response if an exception occurred during discussion comment like
            return response()->json([
                "status" => "error",
                "message" => "Discussion comment like failed",
                "data" => null,
                'error' => $th->getMessage()
            ], 500);
        }
    }

}
