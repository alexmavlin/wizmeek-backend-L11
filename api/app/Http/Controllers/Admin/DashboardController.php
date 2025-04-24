<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Genre;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        $genres = Genre::with(['youTubeVideos.contentType'])->get();

        $mediaStats = [];

        foreach ($genres as $genre) {
            $videoStats = [
                'today' => 0,
                'month' => 0,
                'all_time' => 0,
            ];
            $audioStats = [
                'today' => 0,
                'month' => 0,
                'all_time' => 0,
            ];

            foreach ($genre->youTubeVideos as $video) {
                $type = $video->contentType->name ?? null;

                if (!$type) continue;

                $createdAt = Carbon::parse($video->created_at);

                if ($type === 'Music Video') {
                    $videoStats['all_time']++;
                    if ($createdAt->isToday()) $videoStats['today']++;
                    if ($createdAt->greaterThanOrEqualTo($startOfMonth)) $videoStats['month']++;
                }

                if ($type === 'Music Audio') {
                    $audioStats['all_time']++;
                    if ($createdAt->isToday()) $audioStats['today']++;
                    if ($createdAt->greaterThanOrEqualTo($startOfMonth)) $audioStats['month']++;
                }
            }

            /* $mediaStats[] = [
                'genre' => $genre->genre,
                'video' => $videoStats,
                'audio' => $audioStats,
            ]; */
            $mediaStats[] = [
                'current_month' => date('F', strtotime(Carbon::now())),
                'video' => [
                    'genre' => $genre->genre,
                    'stats' => $videoStats
                ],
                'audio' => [
                    'genre' => $genre->genre,
                    'stats' => $audioStats
                ]
            ];
        }

        $data = [
            "scss" => [
                "resources/scss/admin/dashboard.scss"
            ],
            "js" => [],
            "user_stats" => [
                "total_users" => User::getTotalUsers(),
                "today_registered_users" => User::getTodayRegisteredUsers()
            ],
            "media_stats" => $mediaStats
        ];

        // dd($data);

        return view('admin.dashboard', compact('data'));
    }
}
