<?php

namespace App\Http\Controllers\Api\Feedback;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Feedback\StoreFeedbackRequest;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class StoreFeedbackController extends Controller
{
    public function __invoke(StoreFeedbackRequest $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => "Unable to store a comment.",
                'error' => "No logged in users are found in the current session",
                'data' => []
            ], 401);
        }

        try {
            $storedFiles = [];

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $filePath = ('feedback_files/' . $filename);
                    $file->move(public_path('feedback_files'), $filename);

                    $storedFiles[] = 'feedback_files/' . $filename;
                }
            }

            $result = Feedback::create([
                'user_id' => Auth::user()->id,
                'subject' => $request->subject,
                'message' => $request->message,
                'files' => json_encode($storedFiles)
            ]);
            return response()->json([
                'success' => $result,
                'message' => "Thank you! Your feedback has been successfully submitted. Our team will review it and get back to you as soon as possible.",
                'error' => '',
                'data' => []
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
                'error' => $error->getMessage(),
                'data' => []
            ], 500);
        }
    }
}
