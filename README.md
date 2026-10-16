# Task Manager

Gerenciador de tarefas feito com **Laravel 12**, **MySQL** e **Blade** (Bootstrap 5 via CDN, sem precisar de Node/Vite).

## Funcionalidades

- Login, cadastro e logout (sessão, "lembrar de mim", limite de 5 tentativas de login)
- CRUD de tarefas: título, descrição, status, prioridade e data de entrega
- Marcar como concluída / reabrir com um clique
- Busca por texto e filtros por status e prioridade, com paginação
- Contadores por status e destaque de tarefas atrasadas
- Cada usuário vê e edita apenas as próprias tarefas (via `TaskPolicy`)

## Requisitos

- PHP 8.2+ com as extensões `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`
- Composer
- MySQL 8+ (ou MariaDB 10.6+)

No Windows, o jeito mais fácil é instalar o [Laragon](https://laragon.org) ou o [XAMPP](https://www.apachefriends.org), que já trazem PHP e MySQL.

## Instalação

```bash
cd task-manager
composer install
cp .env.example .env          # no PowerShell: copy .env.example .env
php artisan key:generate
```

Crie o banco no MySQL:

```sql
CREATE DATABASE task_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Ajuste `DB_USERNAME` / `DB_PASSWORD` no `.env` se necessário e rode:

```bash
php artisan migrate --seed
php artisan serve
```

Acesse http://localhost:8000 e entre com o usuário de demonstração:

- **E-mail:** `admin@example.com`
- **Senha:** `password`

## Estrutura

```
app/Http/Controllers/AuthController.php   login, cadastro, logout
app/Http/Controllers/TaskController.php   CRUD, filtros, alternar status
app/Models/Task.php                       model + rótulos de status/prioridade
app/Policies/TaskPolicy.php               garante que só o dono altere a tarefa
database/migrations/                      tabelas users e tasks
database/seeders/DatabaseSeeder.php       usuário e tarefas de exemplo
resources/views/                          layouts, auth e tasks (Blade)
routes/web.php                            rotas
```
