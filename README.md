# Vicman Technologies - API de Gestión de Biblioteca

API RESTful desarrollada con Laravel 12 para la gestión de una biblioteca digital, incluyendo autores, libros, usuarios y préstamos.

## 📋 Tabla de Contenidos

- [Requisitos del Sistema](#requisitos-del-sistema)
- [Instalación](#instalación)
- [Configuración](#configuración)
- [Variables de Entorno](#variables-de-entorno)
- [Configuración de Laravel Sanctum para SPA](#configuración-de-laravel-sanctum-para-spa)
- [Documentación de la API](#-documentación-de-la-api)
  - [Endpoints de Autores](#endpoints-de-autores)
  - [Endpoints de Libros](#endpoints-de-libros)
  - [Endpoints de Usuarios](#endpoints-de-usuarios)
  - [Endpoints de Préstamos](#endpoints-de-préstamos)
- [Integración con IA](#-integración-con-ia)

## 🔧 Requisitos del Sistema

- PHP >= 8.2
- Composer >= 2.0
- MySQL >= 8.0 o MariaDB >= 10.3
- Node.js >= 18.x (para compilación de assets)
- NPM >= 9.x

## 📦 Instalación

### 1. Clonar el repositorio

```bash
git clone <repository-url>
cd vicman-technologies-technical-test-api
```

### 2. Instalar dependencias de PHP

```bash
composer install
```

### 3. Instalar dependencias de Node.js

```bash
npm install
```

### 4. Crear archivo de configuración

Copia el archivo `.env.example` a `.env`:

```bash
cp .env.example .env
```

### 5. Generar clave de aplicación

```bash
php artisan key:generate
```

### 6. Configurar base de datos

Edita el archivo `.env` con tus credenciales de base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

Crea la base de datos en MySQL:

```sql
CREATE DATABASE vicman_technologies_technical_test_api CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 7. Ejecutar migraciones

```bash
php artisan migrate
```

### 8. Generar datos de Seeders desde Open Library API (Opcional)

El proyecto incluye comandos personalizados para generar automáticamente los seeders de autores y libros con datos reales de la API de Open Library.

#### Comando: `populate-author-seeder`

Este comando obtiene información de autores conocidos desde la API de Open Library y genera automáticamente el archivo `AuthorSeeder.php` con datos reales.

**Cómo funciona:**
- Consulta la API de Open Library para una lista de autores conocidos (Gabriel García Márquez, Franz Kafka, J.K. Rowling, etc.)
- Extrae información como nombre, fecha de nacimiento, obra principal, conteo de trabajos y clave de Open Library
- Genera automáticamente el código del seeder en `database/seeders/AuthorSeeder.php`

**Uso:**
```bash
php artisan populate-author-seeder
```

**Salida esperada:**
```
🔍 Obteniendo datos de autores desde Open Library API...

👍 Procesado: Gabriel García Márquez
👍 Procesado: Franz Kafka
👍 Procesado: Julio Verne
...

☑️ Seeder AuthorSeeder.php generado exitosamente!
📝 Total de autores: 20
📂 Ubicación del seeder: /path/to/database/seeders/AuthorSeeder.php
```

#### Comando: `populate-book-seeder`

Este comando obtiene libros de los autores ya guardados en la base de datos desde la API de Open Library y genera automáticamente el archivo `BookSeeder.php`.

**Cómo funciona:**
- Lee todos los autores existentes en la base de datos
- Consulta la API de Open Library para obtener libros de cada autor
- Limita a 10 libros por autor (o menos si no tiene tantos)
- Extrae título, clave de portada, año de primera publicación
- Genera automáticamente el código del seeder en `database/seeders/BookSeeder.php`
- Asigna aleatoriamente entre 5 y 30 unidades disponibles por libro

**Uso:**
```bash
php artisan populate-book-seeder
```

**⚠️ Importante:** Este comando requiere que primero hayas ejecutado las migraciones Y el seeder de autores, ya que necesita autores existentes en la base de datos.

**Salida esperada:**
```
🔍 Obteniendo datos de libros desde Open Library API...

👍 Procesado: Cien años de soledad
👍 Procesado: El amor en los tiempos del cólera
👍 Procesado: La metamorfosis
...

☑️ Seeder BookSeeder.php generado exitosamente!
📝 Total de libros: 150
📂 Ubicación del seeder: /path/to/database/seeders/BookSeeder.php
```

#### Flujo Recomendado para Poblar la Base de Datos

```bash
# 1. Generar seeder de autores con datos de Open Library
php artisan populate-author-seeder

# 2. Ejecutar el seeder de autores para guardarlos en la BD
php artisan db:seed --class=AuthorSeeder

# 3. Generar seeder de libros (necesita autores en la BD)
php artisan populate-book-seeder

# 4. Ejecutar el seeder de libros
php artisan db:seed --class=BookSeeder

# 5. (Opcional) Ejecutar seeder de préstamos
php artisan db:seed --class=LoanSeeder
```

#### Limpieza de seeders

Si en algún momento desea limpiar alguno de estos dos seeders puede hacerlo usando los comandos artisan `clean-author-seeder` y `clean-book-seeder`.

**Ventajas de usar estos comandos:**
- ✅ Datos reales de una API pública reconocida
- ✅ Información completa y precisa sobre autores y libros
- ✅ Generación automática de seeders sin necesidad de escribir datos manualmente
- ✅ Fácil de regenerar con datos actualizados
- ✅ Los seeders generados pueden ser versionados en Git

### 10. Ejecutar seeders (opcional)

Para poblar la base de datos con datos de prueba:

```bash
# Ejecutar todos los seeders
php artisan db:seed

# O ejecutar seeders específicos
php artisan db:seed --class=AuthorSeeder
php artisan db:seed --class=BookSeeder
php artisan db:seed --class=LoanSeeder
```

### 11. Compilar assets del frontend

```bash
# Para desarrollo
npm run dev

# Para producción
npm run build
```

## ⚙️ Configuración

### Comando de Configuración Rápida

Puedes usar el comando personalizado que ejecuta todos los pasos de configuración automáticamente:

```bash
composer setup
```

Este comando ejecuta:
- `composer install`
- Crea `.env` desde `.env.example`
- `php artisan key:generate`
- `php artisan migrate`
- `npm install`
- `npm run build`

## 🔐 Variables de Entorno

### Variables Esenciales de la Aplicación

```env
APP_NAME="Vicman Technologies API"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost:8000
```

### Variables de Base de Datos

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vicman_technologies_technical_test_api
DB_USERNAME=root
DB_PASSWORD=
```

### Variables de Cola (Queue)

```env
QUEUE_CONNECTION=database
```

### Variables de Caché

```env
CACHE_STORE=database
```

### Variables de Sesión

```env
SESSION_DRIVER=database
SESSION_LIFETIME=999999
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
```

## ⛪ Configuración de Laravel Sanctum para SPA

Laravel Sanctum está configurado para trabajar con una Single Page Application (SPA) desarrollada en React.js. A continuación, las variables de entorno necesarias:

### Variables de Sanctum

Agrega las siguientes variables a tu archivo `.env`:

```env
# URL del frontend React
FRONTEND_URL=http://localhost:3000

# Dominio de la sesión (sin puerto ni protocolo)
SESSION_DOMAIN=localhost

# Dominios stateful de Sanctum (incluye todos los puertos comunes de desarrollo)
SANCTUM_STATEFUL_DOMAINS=localhost:3000,localhost:5173,127.0.0.1:3000,127.0.0.1:5173
```

### ¿Qué significa cada variable?

#### `FRONTEND_URL`
- URL completa de tu aplicación React
- Usado para redirecciones y validaciones
- **Ejemplos:**
  - Desarrollo: `http://localhost:3000`
  - Producción: `https://app.ejemplo.com`

#### `SESSION_DOMAIN`
- Dominio base para las cookies de sesión
- **NO incluir** el puerto ni el protocolo
- **Importante:** Debe ser el mismo dominio base que tu frontend
- **Ejemplos:**
  - Desarrollo local: `localhost`
  - Producción: `ejemplo.com`

#### `SANCTUM_STATEFUL_DOMAINS`
- Lista separada por comas de dominios que pueden usar autenticación con sesión
- **Incluir el puerto** para desarrollo local
- Los dominios listados pueden hacer peticiones autenticadas usando cookies
- **Ejemplos:**
  - Desarrollo: `localhost:3000,localhost:5173,127.0.0.1:3000`
  - Producción: `app.ejemplo.com,www.ejemplo.com`

### Configuración CORS

El proyecto ya incluye configuración de CORS para permitir peticiones desde tu SPA React. Los archivos relevantes son:

- `config/cors.php` - Configuración de orígenes permitidos
- `bootstrap/app.php` - Middleware CORS aplicado globalmente

**Configuración actual en desarrollo:**
```php
'allowed_origins' => ['*'], // Permite todos los orígenes en desarrollo
'supports_credentials' => true, // Permite cookies de autenticación
```

**⚠️ Importante para Producción:**
En producción, cambia `'allowed_origins' => ['*']` a un array específico de dominios:

```php
'allowed_origins' => [
    'https://app.ejemplo.com',
    'https://www.ejemplo.com',
],
```

### Ejemplo Completo de Variables Sanctum

#### Para Desarrollo Local

```env
FRONTEND_URL=http://localhost:3000
SESSION_DOMAIN=localhost
SANCTUM_STATEFUL_DOMAINS=localhost:3000,localhost:5173,127.0.0.1:3000,127.0.0.1:5173
```

#### Para Producción

```env
FRONTEND_URL=https://app.midominio.com
SESSION_DOMAIN=midominio.com
SANCTUM_STATEFUL_DOMAINS=app.midominio.com,www.midominio.com
```

### Cómo Conectar desde React

En tu aplicación React, configura axios o fetch para incluir credenciales:

**Con Axios:**
```javascript
import axios from 'axios';

axios.defaults.withCredentials = true;
axios.defaults.baseURL = 'http://localhost:8000';

// Antes de hacer login, obtener el CSRF cookie
await axios.get('/sanctum/csrf-cookie');

// Luego hacer login
await axios.post('/api/login', {
  email: 'usuario@ejemplo.com',
  password: 'password'
});
```

**Con Fetch:**
```javascript
// Configurar fetch para incluir credenciales
fetch('http://localhost:8000/api/users', {
  credentials: 'include', // Importante: envía cookies
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  }
});
```

### Verificar la Configuración

1. **Verificar que Sanctum esté instalado:**
```bash
composer show laravel/sanctum
```

2. **Verificar configuración de Sanctum:**
```bash
php artisan config:show sanctum
```

3. **Limpiar caché de configuración:**
```bash
php artisan config:clear
php artisan cache:clear
```

### Rutas de Autenticación Disponibles

| Método | Endpoint | Descripción | Protegida |
|--------|----------|-------------|-----------|
| POST | `/api/register` | Registrar nuevo usuario | No |
| POST | `/api/login` | Iniciar sesión | No |
| GET | `/api/me` | Obtener usuario actual | Sí |
| POST | `/api/logout` | Cerrar sesión | Sí |

### Troubleshooting

#### Error: "CSRF token mismatch"
- Asegúrate de llamar a `/sanctum/csrf-cookie` antes de hacer login
- Verifica que `withCredentials: true` esté configurado
- Revisa que `SESSION_DOMAIN` coincida con tu dominio

#### Error: "Unauthenticated"
- Verifica que el dominio de tu frontend esté en `SANCTUM_STATEFUL_DOMAINS`
- Asegúrate de incluir `credentials: 'include'` en tus peticiones
- Verifica que las cookies estén habilitadas en el navegador

#### CORS Error
- Revisa `config/cors.php`
- Asegúrate de que `supports_credentials` esté en `true`
- Verifica que tu dominio frontend esté en `allowed_origins` (en producción)

---

## 📚 Documentación de la API

### Endpoints de Autores

Requieren autenticación (`auth:sanctum`). **URL Base:** `/api/authors`

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/authors` | Listar autores (con paginación opcional: `?with_pagination=true&per_page=15`) |
| GET | `/api/authors/{id}` | Obtener autor con sus libros asociados |
| POST | `/api/authors/store` | Crear nuevo autor |
| PUT | `/api/authors/update/{id}` | Actualizar autor |
| DELETE | `/api/authors/delete/{id}` | Eliminar autor (elimina libros en cascada) |

**Headers:** `Authorization: Bearer {token}`, `Content-Type: application/json`

**Crear/Actualizar - Body:**
```json
{
  "name": "string (requerido)",
  "birth_date": "string (opcional)",
  "top_work": "string (opcional)",
  "work_count": "integer (opcional)"
}
```

**Respuestas:**
- `200 OK` / `201 Created`: Operación exitosa
- `422 Unprocessable Entity`: Error de validación
- `500 Internal Server Error`: Error del servidor

**Notas:**
- Sistema busca automáticamente `open_library_key` en Open Library API
- Eliminación en cascada elimina todos los libros del autor
- Paginación: 10 autores por página por defecto

---

### Endpoints de Libros

Requieren autenticación (`auth:sanctum`). **URL Base:** `/api/books`

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/books` | Listar libros (con paginación opcional: `?with_pagination=true&per_page=20`) |
| GET | `/api/books/{id}` | Obtener libro específico |
| POST | `/api/books/store` | Crear nuevo libro |
| PUT | `/api/books/update/{id}` | Actualizar libro |
| DELETE | `/api/books/delete/{id}` | Eliminar libro (elimina préstamos en cascada) |

**Headers:** `Content-Type: application/json`

**Crear/Actualizar - Body:**
```json
{
  "author_id": "integer (requerido en creación)",
  "title": "string (requerido)",
  "first_publish_year": "integer (opcional, min: 1, max: año actual)",
  "units_available": "integer (opcional, min: 0)"
}
```

**Respuestas:**
- `200 OK` / `201 Created`: Operación exitosa
- `422 Unprocessable Entity`: Error de validación
- `500 Internal Server Error`: Error del servidor

**Notas:**
- Cada libro debe estar asociado a un autor existente
- `units_available` se reduce automáticamente al registrar préstamos
- Eliminación en cascada elimina todos los préstamos del libro
- Paginación: 10 libros por página por defecto

### Endpoints de Usuarios

Requieren autenticación (`auth:sanctum`). **URL Base:** `/api/users`

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/users` | Listar usuarios (con paginación opcional: `?with_pagination=true&per_page=15`) |

**Headers:** `Content-Type: application/json`

**Respuestas:**
- `200 OK`: Operación exitosa
- `422 Unprocessable Entity`: Error de validación
- `500 Internal Server Error`: Error del servidor

**Notas:**
- Solo lectura disponible. Crear usuarios mediante `/api/register`
- Paginación: 10 usuarios por página por defecto

### Endpoints de Préstamos

Requieren autenticación (`auth:sanctum`). **URL Base:** `/api/loans`

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| POST | `/api/loans/store` | Registrar nuevo préstamo |

**Headers:** `Content-Type: application/json`

**Crear - Body:**
```json
{
  "user_id": "integer (requerido)",
  "book_id": "integer (requerido)",
  "date": "date YYYY-MM-DD (requerido, no futuro)",
  "return_date": "date YYYY-MM-DD (requerido)"
}
```

**Validaciones:**
- `date` ≤ hoy
- `return_date` > `date`
- `return_date` - `date` ≤ 30 días
- Libro debe tener ≥ 1 unidad disponible

**Respuestas:**
- `201 Created`: Préstamo registrado
- `200 OK`: Libro sin unidades disponibles
- `422 Unprocessable Entity`: Error de validación
- `500 Internal Server Error`: Error del servidor

**Notas:**
- `units_available` se reduce automáticamente en 1 al registrar préstamo
- Eliminación de libro o usuario elimina todos sus préstamos (cascade)
- Máximo 30 días de duración por préstamo

---

## 🤖 Integración con IA

Este proyecto está optimizado para trabajar con **Claude Code** (claude.ai/code), el IDE de Anthropic.

### Archivo de Contexto: `@CLAUDE.md`

El archivo `CLAUDE.md` en la raíz del proyecto actúa como un contextualizador automático para Claude Code. Contiene:

- **Descripción del proyecto** y stack tecnológico (Laravel 12, PHP 8.2+, Vite, Tailwind CSS 4.0)
- **Comandos de desarrollo** para configuración y ejecución
- **Arquitectura del proyecto** incluyendo Bootstrap, Rutas y Estructura de directorios
- **Configuración de CORS y Sanctum** para aplicaciones externas (React/Vue/Angular)
- **Patrones comunes** del proyecto:
  - Patrón Controlador-Servicio-Repositorio (CSR)
  - Estructura de directorios y subdirectorios
  - Convenciones de nombres

### Cómo usar Claude Code con este proyecto

1. **Abrir el proyecto en Claude Code:**
   - Navega a [claude.ai/code](https://claude.ai/code)
   - Abre la carpeta del proyecto

2. **Contexto automático:**
   - Claude automáticamente lee `CLAUDE.md` para entender la arquitectura
   - Todos los comandos, rutas y patrones están contextualizados
   - Las convenciones del proyecto se siguen automáticamente

3. **Tareas soportadas:**
   - Crear nuevas funcionalidades siguiendo el patrón CSR
   - Debuggear y resolver problemas
   - Refactorizar código manteniendo patrones
   - Generar migraciones y modelos
   - Escribir pruebas siguiendo las convenciones del proyecto

### Ventajas

- ✅ Coherencia arquitectónica automática
- ✅ Convenciones de código respetadas
- ✅ Estructura de directorios consistente
- ✅ Comandos de desarrollo listos
- ✅ Documentación siempre accesible

---

## 🚀 Ejecutar el Proyecto

### Modo Desarrollo

```bash
# Iniciar servidor de desarrollo (http://localhost:8000)
php artisan serve

# En otra terminal, iniciar Vite para hot reload de assets
npm run dev
```

### Acceder a la API

- **URL Base:** http://localhost:8000
- **Endpoints API:** http://localhost:8000/api/*

---

**Desarrollado por:** Fahibram Cárcamo
**Proyecto:** Prueba Técnica para la empresa Vicman Technologies
