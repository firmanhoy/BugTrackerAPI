<?php

namespace Database\Factories;

use App\Models\Bug;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BugFactory extends Factory
{
    protected $model = Bug::class;

    public function definition(): array
    {
        $qa  = User::where('role', 'QA')->inRandomOrder()->first() ?? User::factory()->create(['role' => 'QA']);
        $dev = User::where('role', 'DEV')->inRandomOrder()->first() ?? User::factory()->create(['role' => 'DEV']);

        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'reproduction_steps' => "1. " . fake()->sentence() . "\n2. " . fake()->sentence(),
            'severity' => fake()->randomElement(['LOW', 'MEDIUM', 'HIGH', 'CRITICAL']),
            'status' => 'OPEN',
            'reporter_id' => $qa->id,
            'assignee_id' => $dev->id,
        ];
    }
}
