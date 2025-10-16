# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 12 application built for the Vicman Technologies technical test. It uses PHP 8.2+ with Vite for frontend asset bundling and Tailwind CSS 4.0 for styling.

## Development Commands

### Initial Setup
```bash
composer setup
```
This runs: `composer install`, creates `.env` from `.env.example`, generates app key, runs migrations, installs npm dependencies, and builds assets.

### Running the Application
```bash
# Start all development services (server, queue worker, and Vite)
composer dev

# Or start components individually:
php artisan serve              # Development server (http://localhost:8000)
php artisan queue:listen --tries=1  # Queue worker
npm run dev                    # Vite dev server with hot reload
```

### Database
```bash
php artisan migrate                    # Run migrations
php artisan migrate:fresh              # Drop all tables and re-run migrations
php artisan migrate:fresh --seed       # Drop, migrate, and seed
php artisan db:seed                    # Run seeders
php artisan make:migration <name>      # Create new migration
php artisan make:model <name> -m       # Create model with migration
php artisan make:seeder <name>         # Create seeder
```

### Testing
```bash
composer test                          # Run all tests with config clear
php artisan test                       # Run all tests
php artisan test --filter <name>       # Run specific test
php artisan test tests/Feature/<file>  # Run specific test file
```

PHPUnit is configured to use:
- SQLite in-memory database for testing
- Array cache/session/mail drivers
- Tests are in `tests/Feature/` and `tests/Unit/`

### Code Quality
```bash
# Laravel Pint for code formatting (PSR-12 standard)
./vendor/bin/pint                      # Format all files
./vendor/bin/pint --test               # Check formatting without changes
```

### Frontend Assets
```bash
npm run dev                            # Start Vite dev server
npm run build                          # Build production assets
```

## Architecture & Structure

### Application Bootstrap
Laravel 12 uses a streamlined bootstrap process in `bootstrap/app.php`:
- Routes configured via `withRouting()` - currently only web routes and console commands
- Middleware registered via `withMiddleware()`
- Exception handling via `withExceptions()`
- Built-in health check at `/up`

### Routes
- **Web routes**: `routes/web.php` - Traditional web routes
- **Console commands**: `routes/console.php` - Artisan command definitions
- **No API routes file yet** - If adding API routes, register them in `bootstrap/app.php` using `api: __DIR__.'/../routes/api.php'`

### Application Structure
- `app/Http/Controllers/` - HTTP controllers
- `app/Models/` - Eloquent models
- `app/Providers/` - Service providers
- `database/migrations/` - Database migrations
- `database/seeders/` - Database seeders
- `database/factories/` - Model factories
- `resources/views/` - Blade templates
- `resources/css/` & `resources/js/` - Frontend assets (processed by Vite)
- `tests/Feature/` - Feature tests
- `tests/Unit/` - Unit tests

### Configuration
Environment configuration in `.env.example` shows:
- **Database**: MySQL by default (host: 127.0.0.1, database: vicman_technologies_technical_test_api)
- **Queue**: Database-backed queue connection
- **Cache**: Database-backed cache
- **Session**: Database-backed sessions
- **Mail**: Log driver (for development)

### Frontend Tooling
- **Vite**: Asset bundler configured in `vite.config.js`
- **Tailwind CSS 4.0**: Styling framework with `@tailwindcss/vite` plugin
- **Entry points**: `resources/css/app.css` and `resources/js/app.js`

## Common Patterns

### Creating New Features
1. Create migration if database changes needed: `php artisan make:migration`
2. Create model: `php artisan make:model ModelName -m` (with migration)
3. Create controller: `php artisan make:controller ControllerName`
4. Register routes in appropriate route file
5. Create tests in `tests/Feature/` or `tests/Unit/`

### Database Testing
Tests use SQLite in-memory database. Use `RefreshDatabase` trait in test classes to ensure clean state between tests.

### Queue Jobs
Queue connection is set to 'database'. When creating jobs:
1. Create job: `php artisan make:job JobName`
2. Ensure queue worker is running: `php artisan queue:listen --tries=1`
3. Failed jobs table available for job failure handling
