<?php

namespace App\Http\Controllers\Admin\Feedback;

use App\Http\Controllers\Controller;
use App\Models\Feedback;

class FeedbackViewController extends Controller
{
    public function __invoke($id)
    {
        try {
            $feedback = Feedback::getSingleforAdmin($id);
        } catch (\Exception $error) {
            return redirect()->back()->with('error', 'An error has occured during an attempt to load feedback and data. Error: ' . $error->getMessage());
        }

        $data = [
            "scss" => [
                'resources/scss/admin/artists/artists_view.scss'
            ],
            "js" => [

            ],
            "feedback" => $feedback,
        ];

        return view('admin.feedback.feedbackView', compact('data'));
    }
}
