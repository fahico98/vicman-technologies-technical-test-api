# CLAUDE.md

Este archivo proporciona orientación a Claude Code (claude.ai/code) cuando trabaja con código en este repositorio.

## Descripción del Proyecto

Esta es una aplicación Laravel 12 construida para la prueba técnica de Vicman Technologies. Utiliza PHP 8.2+ con Vite para el empaquetado de assets del frontend y Tailwind CSS 4.0 para estilos.

## Comandos de Desarrollo

### Configuración Inicial
```bash
composer setup
```
Esto ejecuta: `composer install`, crea `.env` desde `.env.example`, genera la clave de la aplicación, ejecuta las migraciones, instala las dependencias de npm y construye los assets.

### Ejecutar la Aplicación
```bash
# Iniciar todos los servicios de desarrollo (servidor, queue worker y Vite)
composer dev

# O iniciar componentes individualmente:
php artisan serve              # Servidor de desarrollo (http://localhost:8000)
php artisan queue:listen --tries=1  # Worker de cola
npm run dev                    # Servidor de desarrollo Vite con hot reload
```

### Base de Datos
```bash
php artisan migrate                    # Ejecutar migraciones
php artisan migrate:fresh              # Eliminar todas las tablas y volver a ejecutar migraciones
php artisan migrate:fresh --seed       # Eliminar, migrar y sembrar
php artisan db:seed                    # Ejecutar seeders
php artisan make:migration <nombre>    # Crear nueva migración
php artisan make:model <nombre> -m     # Crear modelo con migración
php artisan make:seeder <nombre>       # Crear seeder
```

### Pruebas
```bash
composer test                          # Ejecutar todas las pruebas limpiando la configuración
php artisan test                       # Ejecutar todas las pruebas
php artisan test --filter <nombre>     # Ejecutar prueba específica
php artisan test tests/Feature/<archivo>  # Ejecutar archivo de prueba específico
```

PHPUnit está configurado para usar:
- Base de datos SQLite en memoria para pruebas
- Drivers de array para cache/session/mail
- Las pruebas están en `tests/Feature/` y `tests/Unit/`

### Calidad de Código
```bash
# Laravel Pint para formateo de código (estándar PSR-12)
./vendor/bin/pint                      # Formatear todos los archivos
./vendor/bin/pint --test               # Verificar formateo sin hacer cambios
```

### Assets del Frontend
```bash
npm run dev                            # Iniciar servidor de desarrollo Vite
npm run build                          # Construir assets de producción
```

## Arquitectura y Estructura

### Bootstrap de la Aplicación
Laravel 12 utiliza un proceso de bootstrap simplificado en `bootstrap/app.php`:
- Rutas configuradas vía `withRouting()` - actualmente solo rutas web y comandos de consola
- Middleware registrado vía `withMiddleware()`
- Manejo de excepciones vía `withExceptions()`
- Health check integrado en `/up`

### Rutas
- **Rutas web**: `routes/web.php` - Rutas web tradicionales
- **Comandos de consola**: `routes/console.php` - Definiciones de comandos Artisan
- **Aún no hay archivo de rutas API** - Si se agregan rutas API, registrarlas en `bootstrap/app.php` usando `api: __DIR__.'/../routes/api.php'`

### Estructura de la Aplicación
- `app/Http/Controllers/` - Controladores HTTP
- `app/Models/` - Modelos Eloquent
- `app/Providers/` - Service providers
- `database/migrations/` - Migraciones de base de datos
- `database/seeders/` - Seeders de base de datos
- `database/factories/` - Factories de modelos
- `resources/views/` - Plantillas Blade
- `resources/css/` & `resources/js/` - Assets del frontend (procesados por Vite)
- `tests/Feature/` - Pruebas de características
- `tests/Unit/` - Pruebas unitarias

### Configuración
La configuración de entorno en `.env.example` muestra:
- **Base de datos**: MySQL por defecto (host: 127.0.0.1, base de datos: vicman_technologies_technical_test_api)
- **Cola**: Conexión de cola respaldada por base de datos
- **Caché**: Caché respaldada por base de datos
- **Sesión**: Sesiones respaldadas por base de datos
- **Correo**: Driver de log (para desarrollo)

### Configuración de CORS y Sanctum

La aplicación está configurada para permitir peticiones desde aplicaciones externas (como React):

**Variables de entorno importantes en `.env`:**
```env
SANCTUM_STATEFUL_DOMAINS=localhost:3000,localhost:5173,127.0.0.1:3000,127.0.0.1:5173
SESSION_DOMAIN=localhost
FRONTEND_URL=http://localhost:3000
```

**Configuración de CORS (`config/cors.php`):**
- Permite todas las rutas `api/*` y `sanctum/csrf-cookie`
- `allowed_origins` configurado en `['*']` para desarrollo (restringir en producción)
- `supports_credentials` en `true` para permitir cookies de autenticación

**Middleware CORS:**
El middleware `HandleCors` está registrado en `bootstrap/app.php` y se aplica automáticamente a todas las rutas API.

**Para aplicaciones React/Vue/Angular:**
1. Asegúrate de incluir `credentials: 'include'` en las peticiones fetch/axios
2. Envía el token en el header `Authorization: Bearer {token}` para rutas protegidas
3. Los puertos comunes (3000, 5173) ya están configurados en `SANCTUM_STATEFUL_DOMAINS`

### Herramientas del Frontend
- **Vite**: Empaquetador de assets configurado en `vite.config.js`
- **Tailwind CSS 4.0**: Framework de estilos con plugin `@tailwindcss/vite`
- **Puntos de entrada**: `resources/css/app.css` y `resources/js/app.js`

## Patrones Comunes

### Crear Nuevas Funcionalidades
1. Crear migración si se necesitan cambios en la base de datos: `php artisan make:migration`
2. Crear modelo: `php artisan make:model NombreModelo -m` (con migración)
3. Crear controlador: `php artisan make:controller NombreControlador`
4. Registrar rutas en el archivo de rutas apropiado
5. Crear pruebas en `tests/Feature/` o `tests/Unit/`

### Pruebas de Base de Datos
Las pruebas usan base de datos SQLite en memoria. Usar el trait `RefreshDatabase` en las clases de prueba para asegurar un estado limpio entre pruebas.

### Jobs de Cola
La conexión de cola está configurada como 'database'. Al crear jobs:
1. Crear job: `php artisan make:job NombreJob`
2. Asegurarse de que el worker de cola esté ejecutándose: `php artisan queue:listen --tries=1`
3. Tabla de trabajos fallidos disponible para manejo de fallos

### Patrón Controlador-Servicio-Repositorio

El proyecto sigue este patrón arquitectónico estricto:

1. **Controladores** (`app/Http/Controllers/`): Manejan las solicitudes HTTP y las dirigen a los servicios. No debe haber lógica de negocio en los controladores.
2. **Servicios** (`app/Services/`): Contienen toda la lógica de negocio. Pueden llamar a otros servicios y repositorios mediante inyección de dependencias.
3. **Repositorios** (`app/Repositories/`): Manejan todas las consultas Eloquent a la base de datos. Cada repositorio está asociado con un modelo. No pueden importar otros repositorios o servicios.

**Reglas Clave:**
- Los controladores pueden importar servicios y repositorios
- Los servicios pueden importar otros servicios y repositorios
- Los repositorios no pueden importar otros repositorios o servicios
- Usar inyección de dependencias, nunca métodos estáticos
- Cada modelo debe tener un controlador, servicio y repositorio con los sufijos apropiados (`Controller.php`, `Service.php`, `Repository.php`)

### Estructura de Directorios

- Los modelos están en `app/Models/` con subdirectorios (ej: `app/Models/User/`, `app/Models/Business/`)
- Controladores, Servicios y Repositorios siguen la misma estructura de subdirectorios que sus modelos
- Los servicios están en `app/Services/` (no `app/Http/Services`)
- Los repositorios están en `app/Repositories/` (no `app/Http/Repositories`)

### Nombres de Directorios y Subdirectorios

Para los siguientes directorios, cada archivo debe estar dentro de un subdirectorio cuyo nombre haga referencia a un modelo o módulo:
- `app/Http/Requests`
- `app/Http/Middlewares`
- `app/Http/Resources`
- `app/Jobs`
- `app/Mail`
- `app/Notifications`
- `app/Observers`
- `app/Console/Commands`
- `database/factories`

**Consideraciones importantes:**
- Cuando un subdirectorio haga referencia a un modelo, el nombre debe ser el mismo del modelo pero en **plural**
- Los nombres de directorios y subdirectorios quedan a criterio del desarrollador
- Pueden existir subdirectorios anidados (ej: `app/Notifications/Users/Interacciones`)
- Todos los subdirectorios que hacen referencia a modelos deben nombrarse en plural (ej: `Requests/Chats` no `Requests/Chat`)