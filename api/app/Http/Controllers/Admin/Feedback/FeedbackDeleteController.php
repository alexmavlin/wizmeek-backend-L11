<?php

namespace App\Http\Controllers\Admin\Feedback;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackDeleteController extends Controller
{
    public function __invoke($id)
    {
        try {
            Feedback::deleteFeedback($id);
            return redirect()->route('admin_feedback_index')->with('success', "Feedback with id: $id deleted successfully.");
        } catch (\Exception $error) {
            return redirect()->back()->with('error', 'An error has occured during an attempt to delete feedback. Error: ' . $error->getMessage());
        }
    }
}
