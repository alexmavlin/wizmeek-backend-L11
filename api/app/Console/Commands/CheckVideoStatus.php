<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\YouTubeVideo;
use Illuminate\Support\Facades\Http;

class CheckVideoStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-video-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the availability of YouTube videos and list unavailable ones';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiKey = config('services.youtube.key');
        if (empty($apiKey)) {
            $this->error('YouTube API key is missing. Set YOUTUBE_API_KEY in your .env file.');
            return Command::FAILURE;
        }

        $this->info('Fetching videos from database...');
        $videos = YouTubeVideo::select('id', 'youtube_id')->get();

        if ($videos->isEmpty()) {
            $this->info('No videos found.');
            return Command::SUCCESS;
        }

        $unavailableVideos = [];

        // Chunk videos in batches of 50
        $videos->chunk(50)->each(function ($chunk) use ($apiKey, &$unavailableVideos) {
            $ids = $chunk->pluck('youtube_id')->implode(',');
            $response = Http::get('https://www.googleapis.com/youtube/v3/videos', [
                'id'   => $ids,
                'key'  => $apiKey,
                'part' => 'status',
            ]);

            if ($response->failed()) {
                foreach ($chunk as $video) {
                    $unavailableVideos[] = [
                        'id' => $video->id,
                        'youtube_id' => $video->youtube_id,
                    ];
                    $this->warn("Failed to fetch video: {$video->youtube_id}");
                }
                return;
            }

            $data = $response->json();
            $availableIds = collect($data['items'] ?? [])->pluck('id')->toArray();

            foreach ($chunk as $video) {
                if (!in_array($video->youtube_id, $availableIds)) {
                    $unavailableVideos[] = [
                        'id' => $video->id,
                        'youtube_id' => $video->youtube_id,
                    ];
                    $this->warn("Unavailable: {$video->youtube_id}");
                } else {
                    $this->line("Available: {$video->youtube_id}");
                }
            }
        });

        foreach ($unavailableVideos as $unavailableVideo) {
            $video = YouTubeVideo::find($unavailableVideo['id']);
            $video->delete();
        }

        // Summary
        $this->newLine();
        $this->info('✅ Check complete.');
        $this->info('Total unavailable videos: ' . count($unavailableVideos));

        if (!empty($unavailableVideos)) {
            $this->table(['ID', 'YouTube ID'], $unavailableVideos);
        }

        return Command::SUCCESS;
    }
}
