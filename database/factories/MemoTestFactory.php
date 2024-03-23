<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\MemoTest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MemoTest>
 */
class MemoTestFactory extends Factory
{
    protected $model = MemoTest::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(2),
            'score' => $this->faker->numberBetween(0, 100),
        ];
    }
}
