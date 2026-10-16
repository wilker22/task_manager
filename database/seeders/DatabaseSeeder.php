<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Administrador', 'password' => 'password'],
        );

        if ($user->tasks()->exists()) {
            return;
        }

        $user->tasks()->createMany([
            ['title' => 'Configurar o ambiente', 'description' => 'Instalar PHP, Composer e MySQL.', 'status' => 'done', 'priority' => 'high', 'due_date' => today()->subDays(2)],
            ['title' => 'Revisar requisitos do projeto', 'description' => 'Ler a especificação e listar dúvidas.', 'status' => 'in_progress', 'priority' => 'medium', 'due_date' => today()->addDay()],
            ['title' => 'Escrever testes', 'description' => null, 'status' => 'pending', 'priority' => 'low', 'due_date' => today()->addWeek()],
            ['title' => 'Enviar relatório semanal', 'description' => 'Resumo do progresso para a equipe.', 'status' => 'pending', 'priority' => 'high', 'due_date' => today()->subDay()],
        ]);
    }
}
