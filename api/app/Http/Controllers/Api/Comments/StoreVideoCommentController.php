<?php

namespace App\Http\Controllers\Api\Comments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Comments\StoreVideoCommentRequest;
use App\Models\VideoComment;
use Illuminate\Support\Facades\Auth;

class StoreVideoCommentController extends Controller
{
    public function __invoke(StoreVideoCommentRequest $request)
    {
        if (!Auth::check())
        {
            return response()->json([
                    'success' => false,
                    'message' => "Unable to store a comment.",
                    'error' => "No logged in users are found in the current session",
                    'data' => []
            ], 401);
        }

        try {
            $result = VideoComment::create([
                "content" => $request->content,
                "user_id" => Auth::user()->id,
                "youtube_video_id" => $request->youtube_video_id
            ]);
            $newComment = VideoComment::getComment($result->id);
            return response()->json([
                'success' => true,
                'message' => "Successfuly created a comment",
                'error' => '',
                'data' => $newComment
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => "Unable to store a comment.",
                'error' => $exception->getMessage(),
                'data' => []
            ], 519);
        }
    }
}
