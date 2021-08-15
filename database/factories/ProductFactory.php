<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $simpTarget = collect([
            'Korone Chwan',
            'Akai Hattooo',
            'Marine Chwan',
            'Master Gawr Gura',
            'Froot',
            'Calli',
            'Phoenix',
            'Amelia Watson',
        ])->random(1)->first();

        return [
            'name' => $this->faker->name(),
            'is_stackable' => rand(1, 5) == 2,
            'price' => rand(1000, 10000),
            'description' => 'Berhenti Simping ' . $simpTarget
        ];
    }
}
