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

## Testes

```bash
docker compose exec app php artisan test
```

| Camada | Tipo | O que cobre |
|---|---|---|
| Value Objects | Unitário (sem banco) | Regras de validação de cada VO |
| TaskService | Unitário (sem banco) | Casos de uso com repositório em memória |
| TaskController | Feature (banco real) | Fluxo HTTP completo com `RefreshDatabase` |

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
| `docker compose exec app php artisan test` | Roda a suíte de testes |
| `docker compose logs -f app` | Acompanha os logs da aplicação |

---

## Arquitetura

O projeto adota **Domain-Driven Design (DDD)** simplificado, organizando o código em três camadas independentes: **Domínio**, **Infraestrutura** e **HTTP**. A regra central é que o domínio não conhece Laravel, Eloquent ou banco de dados.

### Por que DDD?

O projeto tem regras de negócio próprias — status, prioridade, datas de agendamento, visão semanal — que precisam de um lugar claro, separado de HTTP e persistência. DDD resolve isso definindo onde cada responsabilidade vive.

---

### Estrutura de arquivos

```
app/
├── Domain/Task/                          # Domínio — PHP puro, sem dependência de framework
│   ├── DTO/
│   │   ├── CreateTaskInput.php           # Entrada tipada para criação
│   │   ├── UpdateTaskInput.php           # Entrada tipada para atualização
│   │   └── TaskFilters.php               # Filtros de listagem tipados
│   ├── Events/
│   │   ├── TaskCreated.php               # Evento disparado ao criar
│   │   ├── TaskUpdated.php               # Evento disparado ao atualizar
│   │   └── TaskDeleted.php               # Evento disparado ao excluir
│   ├── Listeners/
│   │   ├── LogTaskCreated.php            # Reage ao TaskCreated
│   │   ├── LogTaskUpdated.php            # Reage ao TaskUpdated
│   │   └── LogTaskDeleted.php            # Reage ao TaskDeleted
│   ├── Repositories/
│   │   └── TaskRepositoryInterface.php   # Contrato de persistência
│   ├── Services/
│   │   ├── TaskService.php               # Casos de uso: criar, atualizar, excluir, listar
│   │   └── WeeklyTaskService.php         # Caso de uso: visão semanal
│   ├── ValueObjects/
│   │   ├── TaskTitle.php                 # Não vazio, máx 255 chars
│   │   ├── TaskDescription.php           # Máx 2000 chars
│   │   ├── ScheduledDate.php             # Formato Y-m-d válido, método format()
│   │   └── ScheduledTime.php             # Formato HH:MM válido, método formatted()
│   ├── Task.php                          # Entidade de domínio (readonly class)
│   ├── TaskStatus.php                    # Enum: pending, in_progress, completed, cancelled
│   └── TaskPriority.php                  # Enum: low, medium, high
│
├── Infrastructure/Persistence/           # Infraestrutura — depende de Eloquent
│   ├── Repositories/
│   │   └── EloquentTaskRepository.php    # Implementa TaskRepositoryInterface
│   └── TaskMapper.php                    # Converte TaskModel → TaskEntity
│
├── Http/                                 # Camada HTTP
│   ├── Controllers/
│   │   └── TaskController.php            # Monta DTOs, delega ao service
│   ├── Presenters/
│   │   └── TaskPresenter.php             # Classes CSS de badge (separadas do domínio)
│   └── Requests/
│       └── TaskRequest.php               # Validação de entrada via FormRequest
│
├── Models/
│   └── Task.php                          # Eloquent Model — apenas persistência
│
└── Providers/
    └── AppServiceProvider.php            # Bind de interface → implementação + registro de eventos

tests/
├── Fakes/
│   └── InMemoryTaskRepository.php        # Repositório em memória para testes unitários
├── Unit/Domain/Task/
│   ├── ValueObjects/
│   │   ├── TaskTitleTest.php
│   │   ├── ScheduledDateTest.php
│   │   └── ScheduledTimeTest.php
│   └── Services/
│       └── TaskServiceTest.php           # Service testado sem banco
└── Feature/
    └── TaskControllerTest.php            # Fluxo HTTP completo com banco real
```

---

### Fluxo de uma requisição

```
HTTP Request
  → TaskRequest          (valida os campos de entrada)
  → TaskController       (monta DTOs a partir do request)
  → TaskService          (orquestra o caso de uso)
  → TaskRepositoryInterface
  → EloquentTaskRepository → TaskMapper → Task (entity)
  → event(TaskCreated)   (anuncia o fato ao domínio)
  → LogTaskCreated       (reage de forma independente)
```

---

### Decisões de design

**Value Objects** encapsulam valores com regras próprias. É impossível criar uma `Task` com título vazio, data inválida ou horário fora do formato — a exceção estoura no VO antes de chegar ao banco.

**Repository Interface** desacopla o domínio da persistência. O `TaskService` depende de `TaskRepositoryInterface`, não de Eloquent. Trocar o banco não toca no domínio.

**DTOs** (`CreateTaskInput`, `UpdateTaskInput`, `TaskFilters`) substituem arrays genéricos na entrada do service. Cada campo é tipado — o compilador garante que nenhum campo obrigatório seja esquecido.

**Events/Listeners** garantem que o `TaskService` não acumule responsabilidades. Ao criar uma task, o service dispara `TaskCreated` e não sabe quem vai reagir. Adicionar nova reação (e-mail, Slack) é criar um novo Listener, sem tocar no service.

**TaskMapper** centraliza o mapeamento entre o Eloquent Model e a Entity de domínio. É o único lugar que conhece os dois mundos.

**TaskPresenter** mantém as classes CSS de badge fora do domínio. O enum `TaskStatus` sabe seu label, mas não sabe que existe Bootstrap.

**InMemoryTaskRepository** permite testar o `TaskService` sem banco, sem Docker, sem seed. Os testes unitários rodam em milissegundos.
