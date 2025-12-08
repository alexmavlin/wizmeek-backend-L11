<?php

namespace App\Http\Controllers\Admin\Comments;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\VideoComment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentDeleteController extends Controller
{
    public function __invoke(VideoComment $comment)
    {
        try {
            $comment->delete();
            return redirect()->back()->with('success', 'The comment was deleted successfully.');
        } catch (Exception $error) {
            $message = "Something went wrong while an attempt to remove a comment. Error: " . $error->getMessage();
            Log::error($message);
            return redirect()->back()->with('error', $message);
        }
    }
}
