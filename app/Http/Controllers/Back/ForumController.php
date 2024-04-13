<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\DiscussionComments;
use App\Models\Discussions;
use Illuminate\Http\Request;

class ForumController extends Controller
{

    public function index(Request $request)
    {
        $formQuery = Discussions::with("supervisor");
        $formData = null;

        if ($request->has("status")) {
            $formData = $formQuery->where("status", $request->status)->get();
        }

        $formData = $formQuery->get();

        return view("pages.forum.index", [
            "forums" => $formData,
        ]);
    }

    public function detail($id)
    {
        $formData = Discussions::with(["supervisor", "comments.supervisors"])->find($id);
        return view("pages.forum.detail", [
            "data" => $formData,
        ]);
    }


    public function approved($id)
    {
        $forumFind = Discussions::find($id)->update([
            "status" => "approved",
        ]);

        if ($forumFind) {
            return redirect()->back()->with("success", "Forum approved successfully");
        }
        return redirect()->back()->with("error", "Forum approval failed");
    }

    public function rejected($id)
    {
        $forumFind = Discussions::find($id)->update([
            "status" => "rejected",
        ]);

        if ($forumFind) {
            return redirect()->back()->with("success", "Forum rejected successfully");
        }
        return redirect()->back()->with("error", "Forum rejected failed");
    }

    public function destroyComment($id)
    {
        $discussionComment = DiscussionComments::find($id);
        $delete =  $discussionComment->delete();

        if ($delete) {
            return redirect()->back()->with("success", "Comment deleted successfully");
        }
        return redirect()->back()->with("error", "Comment deleted failed");
    }
}
