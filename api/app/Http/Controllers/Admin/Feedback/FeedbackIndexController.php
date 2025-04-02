<?php

namespace App\Http\Controllers\Admin\Feedback;

use App\Http\Controllers\Controller;
use App\Models\Feedback;

class FeedbackIndexController extends Controller
{
    public function __invoke()
    {
        try {
            $feedbacks = Feedback::getForAdmin();
        } catch (\Exception $error) {
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
