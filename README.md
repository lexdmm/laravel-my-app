# Agendador de Tarefas

MVP de agendamento de tarefas construído com **Laravel 11**, **Blade**, **Bootstrap 5** e **PostgreSQL**, servido via **Docker**.

---

## Funcionalidades

- Criar, editar e excluir tarefas
- Definir data, horário, status e prioridade
- Listagem com filtros por status e período
- Visualização semanal com navegação entre semanas

---

## Pré-requisitos

- [Docker](https://www.docker.com/) e Docker Compose instalados

---

## Setup

### 1. Clone o repositório

```bash
git clone <url-do-repositorio>
cd laravel-my-app
```

### 2. Configure o ambiente

```bash
cp .env.example .env
```

As variáveis já estão configuradas para o Docker. Nenhuma alteração é necessária para rodar localmente.

### 3. Suba os containers

```bash
docker compose up -d
```

Isso inicia três serviços:
- `task_app` — PHP-FPM com a aplicação Laravel
- `task_nginx` — Nginx na porta **8000**
- `task_db` — PostgreSQL 16 na porta **5432**

### 4. Gere a chave da aplicação

```bash
docker compose exec app php artisan key:generate
```

### 5. Execute as migrations

```bash
docker compose exec app php artisan migrate
```

### 6. (Opcional) Carregue dados de demonstração

```bash
docker compose exec app php artisan db:seed
```

Cria 15 tarefas distribuídas pelos dias da semana atual, com diferentes status e prioridades.

---

## Acesso

Abra [http://localhost:8000](http://localhost:8000) no navegador.

---

## Comandos úteis

| Comando | Descrição |
|---|---|
| `docker compose up -d` | Sobe os containers em segundo plano |
| `docker compose down` | Para e remove os containers |
| `docker compose exec app php artisan migrate` | Roda as migrations |
| `docker compose exec app php artisan migrate:fresh --seed` | Recria o banco e carrega os seeds |
| `docker compose exec app php artisan db:seed` | Executa os seeders |
| `docker compose logs -f app` | Acompanha os logs da aplicação |

---

## Estrutura relevante

```
app/
  Http/
    Controllers/TaskController.php   # CRUD + visão semanal
    Requests/TaskRequest.php         # Validação de entrada
  Models/Task.php                    # Model com status/prioridade
database/
  migrations/                        # Schema da tabela tasks
  seeders/TaskSeeder.php             # Dados de demonstração
resources/views/
  layouts/app.blade.php              # Layout principal
  tasks/
    index.blade.php                  # Listagem com filtros
    form.blade.php                   # Criar / editar
    week.blade.php                   # Visão semanal
routes/web.php                       # Rotas da aplicação
docs/roadmap.md                      # Histórico de desenvolvimento
```
