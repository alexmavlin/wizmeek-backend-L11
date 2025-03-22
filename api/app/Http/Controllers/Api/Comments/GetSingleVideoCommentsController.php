<?php

namespace App\Http\Controllers\Api\Comments;

use App\Http\Controllers\Controller;
use App\Models\VideoComment;

class GetSingleVideoCommentsController extends Controller
{
    public function __invoke($video_id)
    {        
        $comments = VideoComment::getForSingleVideo($video_id);

        return response()->json($comments);
    }
}
