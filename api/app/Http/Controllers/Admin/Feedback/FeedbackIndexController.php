<?php

namespace App\Http\Controllers\Admin\Feedback;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Support\Facades\Log;

class FeedbackIndexController extends Controller
{
    public function __invoke()
    {
        try {
            $feedbacks = Feedback::getForAdmin();
        } catch (\Exception $error) {
            $message = 'An error has occured during an attempt to load feedback and data while accessing ' . route('admin_feedback_index') . '. Error: ' . $error->getMessage();
            Log::error($message);
            return redirect()->back()->with('error', 'An error has occured during an attempt to load feedback and data. Error: ' . $error->getMessage());
        }

        $data = [
            "scss" => [
                'resources/scss/admin/artists/artists_index.scss'
            ],
            "js" => [

            ],
            'feedbacks' => $feedbacks
        ];

        return view('admin.feedback.feedbackIndex', compact('data'));
    }
}
