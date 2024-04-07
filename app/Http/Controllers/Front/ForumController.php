<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\DiscussionComments;
use App\Models\Discussions;
use App\Models\Like;
use App\Models\Supervisors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{

    /**
     * Store a newly created discussion in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            "title" => "required",
            "content" => "required",
        ]);

        try {
            // Get supervisor associated with the authenticated user
            $supervisors = Supervisors::where("user_id", Auth::user()->id)->first();

            // Prepare discussion data
            $data = [
                "title" => $request->title,
                "content" => $request->content,
                "view_count" => 0,
                "like_count" => 0,
                "status" => "pending",
                "supervisor_id" => $supervisors->id,
                "approved_id" => null,
            ];

            // Create discussion
            $create = Discussions::create($data);

            // Check if discussion creation was successful
            if ($create) {
                return response(201)->json([
                    "status" => "success",
                    "message" => "Discussion created successfully",
                    "data" => $create
                ]);
            }

            // Return error response if discussion creation failed
            return response(500)->json([
                "status" => "error",
                "message" => "Discussion creation failed",
                "data" => null
            ]);
        } catch (\Throwable $th) {
            // Return error response if an exception occurred during discussion creation
            return response(500)->json([
                "status" => "error",
                "message" => "Discussion creation failed",
                "data" => null
            ]);
        }
    }


    /**
     * Retrieve detailed information about a specific discussion.
     *
     * @param  int  $id The ID of the discussion to retrieve
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail($id)
    {
        try {
            // Retrieve discussion with associated supervisor and comments
            $discussion = Discussions::with(["supervisor", "comments"])->where("id", $id)->first();

            $likeCount = Like::where("discussions_id", $discussion->id)->count();
            $commentCount = DiscussionComments::where("discussion_id", $discussion->id)->count();


            $data = [
                "discussion" => $discussion,
                "like_count" => $likeCount,
                "comment_count" => $commentCount,
                "view_count" => count($discussion->view_count),
            ];

            // Check if discussion exists
            if ($discussion) {
                return response(200)->json([
                    "status" => "success",
                    "message" => "Discussion retrieved successfully",
                    "data" => $data
                ]);
            }

            // Return error response if discussion not found
            return response(500)->json([
                "status" => "error",
                "message" => "Discussion not found",
                "data" => null
            ]);
        } catch (\Throwable $th) {
            // Return error response if an exception occurred during discussion retrieval
            return response(500)->json([
                "status" => "error",
                "message" => "Discussion not found",
                "data" => null
            ]);
        }
    }

    /**
     * Update the specified discussion in the database.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        // Validate incoming request data
        $request->validate([
            "title" => "required",
            "content" => "required",
        ]);

        try {
            // Prepare updated discussion data
            $data = [
                "title" => $request->title,
                "content" => $request->content,
            ];

            // Update discussion
            $update = Discussions::find($id)->update($data);

            // Check if discussion update was successful
            if ($update) {
                return response(201)->json([
                    "status" => "success",
                    "message" => "Discussion updated successfully",
                    "data" => $update
                ]);
            }

            // Return error response if discussion update failed
            return response(500)->json([
                "status" => "error",
                "message" => "Discussion update failed",
                "data" => null
            ]);
        } catch (\Throwable $th) {
            // Return error response if an exception occurred during discussion update
            return response(500)->json([
                "status" => "error",
                "message" => "Discussion update failed",
                "data" => null
            ]);
        }
    }

    /**
     * Remove the specified discussion from the database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // Delete discussion
            $delete = Discussions::find($id)->delete();

            // Check if discussion deletion was successful
            if ($delete) {
                return response()->json([
                    "status" => "success",
                    "message" => "Discussion deleted successfully",
                    "data" => $delete
                ]);
            }

            // Return error response if discussion deletion failed
            return response(500)->json([
                "status" => "error",
                "message" => "Discussion deletion failed",
                "data" => null
            ]);
        } catch (\Throwable $th) {
            // Return error response if an exception occurred during discussion deletion
            return response(500)->json([
                "status" => "error",
                "message" => "Discussion deletion failed",
                "data" => null
            ]);
        }
    }

    /**
     * Like or unlike the specified discussion.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function like($id)
    {
        try {
            // Check if the user has already liked the discussion
            $likeFind = Like::where("discussions_id", $id)->where("supervisor_id", auth()->user()->supervisor->id)->first();

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
