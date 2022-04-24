<?php

namespace Database\Factories;

use DB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $id = DB::table('users')->get('id')->toArray();
        return [
            'post_title' => $this->faker->name(),
            'post_body' => $this->faker->name(),
            'user_id' => isset($id[0]->id) ? $id[0]->id : ''
        ];
    }
}
