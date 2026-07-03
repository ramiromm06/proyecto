<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'title' => ucfirst($this->faker->sentence(4)),
            'description' => $this->faker->optional()->paragraph(),
            'status' => $this->faker->randomElement(['pendiente', 'en_progreso', 'completada']),
            'priority' => $this->faker->randomElement(['baja', 'media', 'alta']),
            'due_date' => $this->faker->optional()->dateTimeBetween('now', '+2 months'),
            'assignee_id' => User::factory(),
        ];
    }
}
