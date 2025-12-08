<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\YouTubeVideo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VideoComment>
 */
class VideoCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => $this->faker->realText(80),
            'user_id' => User::inRandomOrder()->first()->id,
            'youtube_video_id' => YouTubeVideo::inRandomOrder()->first()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
