<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $monday = Carbon::now()->startOfWeek(Carbon::MONDAY);

        $tasks = [
            // Segunda
            [
                'title'          => 'Reunião de planejamento semanal',
                'description'    => 'Alinhar prioridades com o time.',
                'scheduled_date' => $monday->toDateString(),
                'scheduled_time' => '09:00:00',
                'status'         => 'completed',
                'priority'       => 'high',
            ],
            [
                'title'          => 'Revisar pull requests pendentes',
                'scheduled_date' => $monday->toDateString(),
                'scheduled_time' => '14:00:00',
                'status'         => 'completed',
                'priority'       => 'medium',
            ],

            // Terça
            [
                'title'          => 'Implementar tela de relatórios',
                'description'    => 'Criar os filtros por período e exportação CSV.',
                'scheduled_date' => $monday->copy()->addDay()->toDateString(),
                'scheduled_time' => '10:00:00',
                'status'         => 'in_progress',
                'priority'       => 'high',
            ],
            [
                'title'          => 'Atualizar dependências do projeto',
                'scheduled_date' => $monday->copy()->addDay()->toDateString(),
                'scheduled_time' => null,
                'status'         => 'pending',
                'priority'       => 'low',
            ],

            // Quarta
            [
                'title'          => 'Corrigir bug no cálculo de totais',
                'description'    => 'Valores negativos não estão sendo tratados corretamente.',
                'scheduled_date' => $monday->copy()->addDays(2)->toDateString(),
                'scheduled_time' => '09:30:00',
                'status'         => 'in_progress',
                'priority'       => 'high',
            ],
            [
                'title'          => 'Escrever documentação da API',
                'scheduled_date' => $monday->copy()->addDays(2)->toDateString(),
                'scheduled_time' => '15:00:00',
                'status'         => 'pending',
                'priority'       => 'medium',
            ],
            [
                'title'          => 'Agendar revisão com cliente',
                'scheduled_date' => $monday->copy()->addDays(2)->toDateString(),
                'scheduled_time' => null,
                'status'         => 'pending',
                'priority'       => 'medium',
            ],

            // Quinta
            [
                'title'          => 'Deploy em staging',
                'description'    => 'Subir versão 1.2.0 para homologação.',
                'scheduled_date' => $monday->copy()->addDays(3)->toDateString(),
                'scheduled_time' => '11:00:00',
                'status'         => 'pending',
                'priority'       => 'high',
            ],
            [
                'title'          => 'Revisar layout do dashboard',
                'scheduled_date' => $monday->copy()->addDays(3)->toDateString(),
                'scheduled_time' => '14:30:00',
                'status'         => 'pending',
                'priority'       => 'low',
            ],

            // Sexta
            [
                'title'          => 'Apresentação dos resultados da sprint',
                'description'    => 'Demo para o cliente com as entregas da semana.',
                'scheduled_date' => $monday->copy()->addDays(4)->toDateString(),
                'scheduled_time' => '10:00:00',
                'status'         => 'pending',
                'priority'       => 'high',
            ],
            [
                'title'          => 'Retrospectiva da sprint',
                'scheduled_date' => $monday->copy()->addDays(4)->toDateString(),
                'scheduled_time' => '16:00:00',
                'status'         => 'pending',
                'priority'       => 'medium',
            ],
            [
                'title'          => 'Organizar tarefas da próxima semana',
                'scheduled_date' => $monday->copy()->addDays(4)->toDateString(),
                'scheduled_time' => null,
                'status'         => 'pending',
                'priority'       => 'low',
            ],

            // Sábado
            [
                'title'          => 'Backup do banco de dados',
                'scheduled_date' => $monday->copy()->addDays(5)->toDateString(),
                'scheduled_time' => '08:00:00',
                'status'         => 'pending',
                'priority'       => 'medium',
            ],

            // Domingo
            [
                'title'          => 'Preparar pauta da próxima semana',
                'scheduled_date' => $monday->copy()->addDays(6)->toDateString(),
                'scheduled_time' => null,
                'status'         => 'pending',
                'priority'       => 'low',
            ],

            // Tarefa cancelada (exemplo)
            [
                'title'          => 'Migrar servidor legado',
                'description'    => 'Adiado para a próxima sprint.',
                'scheduled_date' => $monday->copy()->addDays(1)->toDateString(),
                'scheduled_time' => null,
                'status'         => 'cancelled',
                'priority'       => 'high',
            ],
        ];

        foreach ($tasks as $data) {
            Task::create($data);
        }
    }
}
