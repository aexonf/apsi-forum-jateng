<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\DiscussionCommentLikes;
use App\Models\DiscussionComments;
use App\Models\Discussions;
use App\Models\LikeDiscussions;
use App\Models\Supervisors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{

    public function index(Request $request)
    {
        try {
            $sort = $request->sort;
            if ($sort == "newest") {
                $discussion = Discussions::with(["supervisor", "comments"])->where("status", "approved")->orderBy("created_at", "desc")->get();
            } elseif ($sort == "oldest") {
                $discussion = Discussions::with(["supervisor", "comments"])->where("status", "approved")->orderBy("created_at", "asc")->get();
            } else {
                $discussion = Discussions::with(["supervisor", "comments"])->where("status", "approved")->get();
            }

            foreach ($discussion as $value) {
                $supervisor = auth()->guard("sanctum")->user()->supervisor;
                $like = LikeDiscussions::where("discussions_id", $value->id)->where("supervisor_id", $supervisor->id)->get()->first();
                $commentCount = DiscussionComments::where("discussion_id", $value->id)->count();

                $value->like_count = $like ? $like->count() : 0;
                $value->comment_count = $commentCount;
                if ($like) {
                    $value->like_discussion = true;
                } else {
                    $value->like_discussion = false;
                }
            }

            // $likeDiscussion = LikeDiscussions::where("supervisor_id", $supervisors->id)->get();

            return response()->json([
                "status" => "success",
                "message" => "Discussion retrieved successfully",
                "data" => $discussion,
                // "likeDiscussion" => $likeDiscussion
            ], 200);
        } catch (\Throwable $th) {
            // Return error response if an exception occurred during discussion retrieval
            return response()->json([
                "status" => "error",
                "message" => "Discussion not found",
                "data" => null,
                "error" => $th->getMessage()
            ], 500);
        }
    }


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
                "status" => "pending",
                "supervisor_id" => $supervisors->id,
                "approved_id" => null,
            ];

            // Create discussion
            $create = Discussions::create($data);

            // Check if discussion creation was successful
            if ($create) {
                return response()->json([
                    "status" => "success",
                    "message" => "Discussion created successfully",
                    "data" => $create
                ], 201);
            }

            // Return error response if discussion creation failed
            return response()->json([
                "status" => "error",
                "message" => "Discussion creation failed",
                "data" => null
            ], 500);
        } catch (\Throwable $th) {
            // Return error response if an exception occurred during discussion creation
            return response()->json([
                "status" => "error",
                "message" => "Discussion creation failed",
                "data" => null,
                "error" => $th->getMessage()
            ], 500);
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
            $updateView = Discussions::find($id)->update([
                "view_count" => Discussions::find($id)->view_count + 1,
            ]);
            $discussion = Discussions::with(["supervisor:id,name,label,img_url"])->where("id", $id)->first();

            $discussion['like_count'] = LikeDiscussions::where("discussions_id", $discussion->id)->count();
            $discussion['comment_count'] = DiscussionComments::where("discussion_id", $discussion->id)->count();

            $likeDiscussion = LikeDiscussions::with(["discussions", "supervisors"])->where("discussions_id", $discussion->id)->exists();
            $comments = DiscussionComments::with(["supervisors"])->where("discussion_id", $discussion->id)->get();

            // Check if discussion exists
            if ($discussion && $updateView) {

                return response()->json([
                    "status" => "success",
                    "message" => "Discussion retrieved successfully",
                    "data" => $discussion,
                    "comments" => $comments,
                    "like_discussion" => $likeDiscussion,
                ], 200);
            }

            // Return error response if discussion not found
            return response()->json([
                "status" => "error",
                "message" => "Discussion not found",
                "data" => null
            ], 500);
        } catch (\Throwable $th) {
            // Return error response if an exception occurred during discussion retrieval
            return response()->json([
                "status" => "error",
                "message" => "Discussion not found",
                "data" => null,
                "error" => $th->getMessage()
            ], 500);
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
            $update = Discussions::find($id);

            // Check if discussion update was successful
            if ($update) {
                return response()->json([
                    "status" => "success",
                    "message" => "Discussion updated successfully",
                    "data" => $update
                ], 201);
            }

            // Return error response if discussion update failed
            return response()->json([
                "status" => "error",
                "message" => "Discussion update failed",
                "data" => null
            ], 500);
        } catch (\Throwable $th) {
            // Return error response if an exception occurred during discussion update
            return response()->json([
                "status" => "error",
                "message" => "Discussion update failed",
                "data" => null
            ], 500);
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
            $discussion = Discussions::findOrFail($id);
            $delete = $discussion->delete();

            // Check if discussion deletion was successful
            if ($delete) {
                return response()->json([
                    "status" => "success",
                    "message" => "Discussion deleted successfully",
                    "data" => $delete
                ]);
            }

            // Return error response if discussion deletion failed
            return response()->json([
                "status" => "error",
                "message" => "Discussion deletion failed",
                "data" => null
            ], 500);
        } catch (\Throwable $th) {
            // Return error response if an exception occurred during discussion deletion
            return response()->json([
                "status" => "error",
                "message" => "Discussion deletion failed",
                "data" => null,
                "error" => $th->getMessage()
            ], 500);
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
            $likeFind = LikeDiscussions::where("discussions_id", $id)->where("supervisor_id", auth()->user()->supervisor->id)->first();

            // If user has already liked the discussion, unlike it
            if ($likeFind) {
                $likeDelete = LikeDiscussions::where("discussions_id", $id)->where("supervisor_id", auth()->user()->supervisor->id)->delete();

                // Check if discussion like deletion was successful
                if ($likeDelete) {
                    return response()->json([
                        "status" => "success",
                        "message" => "Discussion unliked successfully",
                        "data" => $likeDelete
                    ]);
                }

                // Return error response if discussion like deletion failed
                return response()->json([
                    "status" => "error",
                    "message" => "Discussion like deletion failed",
                    "data" => null
                ], 500);
            }

            // Like the discussion
            $discussionLike = LikeDiscussions::create([
                "discussions_id" => $id,
                "supervisor_id" => auth()->user()->supervisor->id,
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
            return response()->json([
                "status" => "error",
                "message" => "Discussion like failed",
                "data" => null
            ], 500);
        } catch (\Throwable $th) {
            // Return error response if an exception occurred during discussion like
            return response()->json([
                "status" => "error",
                "message" => "Discussion like failed",
                "data" => null,
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function myForum(Request $request)
    {
        try {
            $sort = $request->sort;
            $user = Supervisors::where("user_id", auth()->user()->id)->first();
            $discussion = [];

            if ($sort == "newest") {
                $discussion = Discussions::with("supervisor")
                    ->where("supervisor_id", $user->id)
                    ->orderBy("created_at", "desc")
                    ->get();
            } elseif ($sort == "latest") {
                $discussion = Discussions::with("supervisor")
                    ->where("supervisor_id", $user->id)
                    ->orderBy("created_at", "asc")
                    ->get();
            } else {
                $discussion = Discussions::with("supervisor")
                    ->where("supervisor_id", $user->id)
                    ->orderBy("status")
                    ->get();
            }


            foreach ($discussion as $value) {
                $likeCount = LikeDiscussions::where("discussions_id", $value->id)->count();
                $commentCount = DiscussionComments::where("discussion_id", $value->id)->count();
                $like = LikeDiscussions::where("discussions_id", $value->id)->where("supervisor_id", $user->id)->get()->first();

                $value->like_count = $likeCount;
                $value->comment_count = $commentCount;
                if ($like) {
                    $value->like_discussion = true;
                } else {
                    $value->like_discussion = false;
                }
            }

            // Check if discussion exists
            if ($discussion) {
                return response()->json([
                    "status" => "success",
                    "message" => "Discussion retrieved successfully",
                    "data" => $discussion ?? [],
                    "sort" => $sort
                ], 200);
            }
        } catch (\Throwable $th) {
            // Return error response if an exception occurred during discussion retrieval
            return response()->json([
                "status" => "error",
                "message" => "Discussion not found",
                "data" => null,
                "error" => $th->getMessage()
            ], 404);
        }
    }
}
