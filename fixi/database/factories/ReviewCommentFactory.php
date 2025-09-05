<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReviewComment>
 */
class ReviewCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $review = Review::inRandomOrder()->first();
        return [
            'user_id' => User::where('id', '!=', $review->client_id)->inRandomOrder()->first()->id,
            'review_id' => $review->id,
            'comment' => fake()->paragraph(),
        ];
    }
}
