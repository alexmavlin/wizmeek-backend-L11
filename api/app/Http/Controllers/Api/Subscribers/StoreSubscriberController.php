<?php

namespace App\Http\Controllers\Api\Subscribers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Subscribers\StoreSubscriberRequest;
use App\Models\Subscriber;
use Exception;

class StoreSubscriberController extends Controller
{
    public function __invoke(StoreSubscriberRequest $request): \Illuminate\Http\JsonResponse
    {
        $subscriberData = [
            'email' => $request->email,
        ];

        try {
            $newSubscriber = Subscriber::create($subscriberData);

            return response()->json([
                'success' => true,
                'message' => 'Successfully created a new subscriber',
                'data' => [
                    'email' => $newSubscriber->email,
                    'created_at' => $newSubscriber->created_at
                ],
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to create a new subscriber',
                'error' => $exception->getMessage(), // Consider returning just the message
                'data' => [
                    'email' => $subscriberData['email'],
                ],
            ], 200); // Optionally set a status code for the response
        }
    }
}