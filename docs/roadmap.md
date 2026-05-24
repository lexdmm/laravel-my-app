# Roadmap — Agendador de Tarefas (MVP)

> Laravel 11 + Blade + PostgreSQL + Docker
>
> Escopo MVP: sem login, sem testes, sem arquitetura hexagonal.
> Cada etapa termina com uma revisão. Digite **next** para avançar.

---

## Etapa 1 — Infraestrutura e Ambiente ✅ Concluída

- [x] `docker-compose.yml` com serviços: `app` (PHP-FPM), `nginx`, `db` (PostgreSQL 16)
- [x] `Dockerfile` configurado com PHP + extensões necessárias
- [x] Volumes e rede interna (`task_network`)
- [x] `.env.example` com variáveis de banco e app
- [x] Nginx configurado para servir o Laravel

---

## Etapa 2 — Banco de Dados e Model ✅ Concluída

- [x] Migration `create_tasks_table` com colunas:
  - `title`, `description`, `scheduled_date`, `scheduled_time`
  - `status` (enum: pending, in_progress, completed, cancelled)
  - `priority` (enum: low, medium, high)
  - `timestamps`
- [x] Índices em `scheduled_date` e `status`
- [x] Model `Task` com `$fillable`, `$casts`, constantes `STATUSES` e `PRIORITIES`
- [x] Accessors `status_label` e `priority_label`
- [x] Métodos `statusBadgeClass()` e `priorityBadgeClass()` para Bootstrap

---

## Etapa 3 — Rotas, Controller e Validação ✅ Concluída

- [x] `TaskController` com: `index`, `create`, `store`, `edit`, `update`, `destroy`, `week`
- [x] `TaskRequest` (Form Request) com validação e mensagens em PT-BR
- [x] Rotas resource + rota dedicada `GET /tasks/week`
- [x] Redirect da raiz `/` para `tasks.index`
- [x] `create()` aceita `?date=` para pré-preencher a data na visão semanal

---

## Etapa 4 — Views Blade ✅ Concluída

- [x] `resources/views/layouts/app.blade.php` — navbar com links "Semana" e "Nova Tarefa"
- [x] `resources/views/tasks/index.blade.php`
  - Filtros por status, data de início e data de fim
  - Tabela com título, data, horário, prioridade, status, ações
  - Badges coloridos por status e prioridade
  - Paginação (15 por página)
  - Confirmação de exclusão via `confirm()`
- [x] `resources/views/tasks/form.blade.php`
  - Formulário único para criação e edição
  - Todos os campos com validação inline
  - Status e prioridade pré-selecionados (pending/medium para novas tarefas)
- [x] `resources/views/tasks/week.blade.php`
  - Grade com os 7 dias da semana em cards
  - Navegação anterior/próxima semana via `?start=YYYY-MM-DD`
  - Destaque do dia atual
  - Badges de status e prioridade por tarefa
  - Editar e excluir diretamente da visão semanal
  - Botão "Adicionar" por dia (pré-preenche a data no formulário)

---

## Etapa 5 — Finalização ✅ Concluída

- [x] `database/seeders/TaskSeeder.php` — 15 tarefas distribuídas na semana atual (segunda a domingo), com mix de status e prioridades
- [x] `DatabaseSeeder` atualizado para chamar `TaskSeeder` (removido User factory desnecessário para MVP)
- [x] `README.md` reescrito com instruções de setup via Docker, comandos úteis e estrutura do projeto

---

## Estado atual

| Camada          | Arquivo                                     | Status    |
|-----------------|---------------------------------------------|-----------|
| Infra           | `docker-compose.yml`, `Dockerfile`          | ✅ Pronto |
| Banco           | Migration `create_tasks_table`              | ✅ Pronto |
| Model           | `app/Models/Task.php`                       | ✅ Pronto |
| Controller      | `app/Http/Controllers/TaskController.php`   | ✅ Pronto |
| Validação       | `app/Http/Requests/TaskRequest.php`         | ✅ Pronto |
| Rotas           | `routes/web.php`                            | ✅ Pronto |
| Layout          | `resources/views/layouts/app.blade.php`     | ✅ Pronto |
| View: listagem  | `resources/views/tasks/index.blade.php`     | ✅ Pronto |
| View: semana    | `resources/views/tasks/week.blade.php`      | ✅ Pronto |
| View: formulário| `resources/views/tasks/form.blade.php`      | ✅ Pronto |
| Seed            | `database/seeders/TaskSeeder.php`           | ✅ Pronto |
| README          | `README.md`                                 | ✅ Pronto |
