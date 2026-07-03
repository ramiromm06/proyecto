<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Label;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        $admin = User::factory()->create([
            'name' => 'Administrador Demo',
            'email' => 'admin@gestorpro.test',
        ]);
        $admin->assignRole('admin');

        $lider = User::factory()->create([
            'name' => 'Lider Demo',
            'email' => 'lider@gestorpro.test',
        ]);
        $lider->assignRole('lider');

        $colaborador = User::factory()->create([
            'name' => 'Colaborador Demo',
            'email' => 'colaborador@gestorpro.test',
        ]);
        $colaborador->assignRole('colaborador');

        $invitado = User::factory()->create([
            'name' => 'Invitado Demo',
            'email' => 'invitado@gestorpro.test',
        ]);
        $invitado->assignRole('invitado');

        $otrosUsuarios = User::factory(6)->create();
        $otrosUsuarios->each(fn (User $user) => $user->assignRole('colaborador'));
        $labels = Label::factory(5)->create();

        // Proyecto principal con los 4 usuarios semilla como miembros.
        $proyectoPrincipal = Project::factory()->create([
            'name' => 'Lanzamiento GestorPro',
            'owner_id' => $lider->id,
        ]);

        $proyectoPrincipal->members()->attach([
            $lider->id => ['project_role' => 'lider'],
            $colaborador->id => ['project_role' => 'colaborador'],
            $invitado->id => ['project_role' => 'invitado'],
        ]);

        $this->crearTareasConComentarios($proyectoPrincipal, $otrosUsuarios->push($colaborador), $labels);

        // Proyectos adicionales con datos aleatorios para poblar los listados.
        Project::factory(4)->create()->each(function (Project $project) use ($otrosUsuarios, $labels) {
            $miembros = $otrosUsuarios->random(3);
            $roles = ['lider', 'colaborador', 'invitado'];

            $project->members()->attach(
                $miembros->mapWithKeys(fn (User $user, int $i) => [
                    $user->id => ['project_role' => $roles[$i % count($roles)]],
                ])
            );

            $this->crearTareasConComentarios($project, $miembros, $labels);
        });
    }

    private function crearTareasConComentarios(Project $project, $usuarios, $labels): void
    {
        Task::factory(random_int(3, 6))->create([
            'project_id' => $project->id,
            'assignee_id' => $usuarios->random()->id,
        ])->each(function (Task $task) use ($usuarios, $labels) {
            $task->labels()->attach($labels->random(random_int(0, 2))->pluck('id'));

            Comment::factory(random_int(0, 3))->create([
                'task_id' => $task->id,
                'user_id' => $usuarios->random()->id,
            ]);
        });
    }
}
