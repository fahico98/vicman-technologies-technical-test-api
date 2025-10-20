# Vicman Technologies - API de Gestión de Biblioteca

API RESTful desarrollada con Laravel 12 para la gestión de una biblioteca digital, incluyendo autores, libros, usuarios y préstamos.

## 📋 Tabla de Contenidos

- [Requisitos del Sistema](#requisitos-del-sistema)
- [Instalación](#instalación)
- [Configuración](#configuración)
- [Variables de Entorno](#variables-de-entorno)
- [Configuración de Laravel Sanctum para SPA](#configuración-de-laravel-sanctum-para-spa)

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

Todas las rutas de autores requieren autenticación con Laravel Sanctum (middleware `auth:sanctum`).

**URL Base:** `/api/authors`

#### 1. Listar todos los autores

Obtiene una colección de todos los autores, con soporte de paginación opcional.

**Endpoint:** `GET /api/authors`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Query Parameters:**
- `with_pagination` (boolean, opcional): Si es `true`, retorna resultados paginados
- `per_page` (integer, opcional): Número de resultados por página (default: 10)

**Ejemplo sin paginación:**
```bash
curl -X GET "http://localhost:8000/api/authors" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

**Respuesta exitosa (200 OK):**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Gabriel García Márquez",
      "birth_date": "1927-03-06",
      "top_work": "Cien años de soledad",
      "work_count": 150,
      "open_library_key": "/authors/OL13849A",
      "created_at": "2025-10-20T10:00:00.000000Z",
      "updated_at": "2025-10-20T10:00:00.000000Z"
    },
    {
      "id": 2,
      "name": "Franz Kafka",
      "birth_date": "1883-07-03",
      "top_work": "La metamorfosis",
      "work_count": 89,
      "open_library_key": "/authors/OL19976A",
      "created_at": "2025-10-20T10:05:00.000000Z",
      "updated_at": "2025-10-20T10:05:00.000000Z"
    }
  ]
}
```

**Ejemplo con paginación:**
```bash
curl -X GET "http://localhost:8000/api/authors?with_pagination=true&per_page=15" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

**Respuesta paginada (200 OK):**
```json
{
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "name": "Gabriel García Márquez",
        "birth_date": "1927-03-06",
        ...
      }
    ],
    "first_page_url": "http://localhost:8000/api/authors?page=1",
    "from": 1,
    "last_page": 2,
    "last_page_url": "http://localhost:8000/api/authors?page=2",
    "next_page_url": "http://localhost:8000/api/authors?page=2",
    "path": "http://localhost:8000/api/authors",
    "per_page": 15,
    "prev_page_url": null,
    "to": 15,
    "total": 20
  }
}
```

#### 2. Obtener un autor específico

Retorna la información de un autor junto con todos sus libros asociados.

**Endpoint:** `GET /api/authors/{author_id}`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Ejemplo:**
```bash
curl -X GET "http://localhost:8000/api/authors/1" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

**Respuesta exitosa (200 OK):**
```json
{
  "data": {
    "id": 1,
    "name": "Gabriel García Márquez",
    "birth_date": "1927-03-06",
    "top_work": "Cien años de soledad",
    "work_count": 150,
    "open_library_key": "/authors/OL13849A",
    "created_at": "2025-10-20T10:00:00.000000Z",
    "updated_at": "2025-10-20T10:00:00.000000Z",
    "books": [
      {
        "id": 1,
        "title": "Cien años de soledad",
        "author_id": 1,
        "open_library_cover_key": "OL123456W",
        "first_publish_year": 1967,
        "units_available": 15,
        "created_at": "2025-10-20T10:30:00.000000Z",
        "updated_at": "2025-10-20T10:30:00.000000Z"
      },
      {
        "id": 2,
        "title": "El amor en los tiempos del cólera",
        "author_id": 1,
        "open_library_cover_key": "OL789012W",
        "first_publish_year": 1985,
        "units_available": 8,
        "created_at": "2025-10-20T10:31:00.000000Z",
        "updated_at": "2025-10-20T10:31:00.000000Z"
      }
    ]
  }
}
```

#### 3. Crear un nuevo autor

Crea un nuevo autor en la base de datos. El sistema automáticamente intenta obtener información adicional desde Open Library API.

**Endpoint:** `POST /api/authors/store`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json
```

**Body (JSON):**
```json
{
  "name": "Isabel Allende",
  "birth_date": "1942-08-02",
  "top_work": "La casa de los espíritus",
  "work_count": 75
}
```

**Campos de validación:**
- `name` (string, requerido, max: 255): Nombre del autor
- `birth_date` (string, opcional, max: 255): Fecha de nacimiento
- `top_work` (string, opcional, max: 255): Obra principal
- `work_count` (integer, opcional, min: 0): Número de obras publicadas

**Ejemplo:**
```bash
curl -X POST "http://localhost:8000/api/authors/store" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Isabel Allende",
    "birth_date": "1942-08-02",
    "top_work": "La casa de los espíritus",
    "work_count": 75
  }'
```

**Respuesta exitosa (201 Created):**
```json
{
  "message": "Autor creado exitosamente",
  "data": {
    "id": 21,
    "name": "Isabel Allende",
    "birth_date": "1942-08-02",
    "top_work": "La casa de los espíritus",
    "work_count": 75,
    "open_library_key": "/authors/OL26320A",
    "created_at": "2025-10-20T15:00:00.000000Z",
    "updated_at": "2025-10-20T15:00:00.000000Z"
  }
}
```

**Respuesta de error de validación (422 Unprocessable Entity):**
```json
{
  "message": "The name field is required.",
  "errors": {
    "name": [
      "El nombre del autor es obligatorio."
    ]
  }
}
```

**Respuesta de error del servidor (500 Internal Server Error):**
```json
{
  "message": "Error al crear el autor",
  "error": "Detalle del error..."
}
```

#### 4. Actualizar un autor existente

Actualiza la información de un autor. Permite actualizaciones parciales.

**Endpoint:** `PUT /api/authors/update/{author_id}`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json
```

**Body (JSON):**
```json
{
  "name": "Gabriel García Márquez",
  "work_count": 165
}
```

**Campos de validación:**
- Todos los campos son opcionales
- Si se envía `name`, es requerido (validación: `sometimes|required`)

**Ejemplo:**
```bash
curl -X PUT "http://localhost:8000/api/authors/update/1" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "work_count": 165
  }'
```

**Respuesta exitosa (200 OK):**
```json
{
  "message": "Autor actualizado exitosamente",
  "data": {
    "id": 1,
    "name": "Gabriel García Márquez",
    "birth_date": "1927-03-06",
    "top_work": "Cien años de soledad",
    "work_count": 165,
    "open_library_key": "/authors/OL13849A",
    "created_at": "2025-10-20T10:00:00.000000Z",
    "updated_at": "2025-10-20T16:30:00.000000Z"
  }
}
```

**Respuesta de error (500 Internal Server Error):**
```json
{
  "message": "Error al actualizar el autor",
  "error": "Detalle del error..."
}
```

#### 5. Eliminar un autor

Elimina un autor de la base de datos. **Importante:** Todos los libros asociados también serán eliminados debido al `onDelete('cascade')`.

**Endpoint:** `DELETE /api/authors/delete/{author_id}`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Ejemplo:**
```bash
curl -X DELETE "http://localhost:8000/api/authors/delete/1" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

**Respuesta exitosa (200 OK):**
```json
{
  "message": "Autor eliminado exitosamente"
}
```

### Códigos de Estado HTTP

| Código | Significado | Cuándo se usa |
|--------|-------------|---------------|
| 200 | OK | Operación exitosa (GET, PUT, DELETE) |
| 201 | Created | Recurso creado exitosamente (POST) |
| 422 | Unprocessable Entity | Error de validación |
| 500 | Internal Server Error | Error del servidor |
| 401 | Unauthorized | Token de autenticación inválido o faltante |

### Notas Importantes

1. **Autenticación requerida:** Todas las rutas de autores requieren un token de autenticación válido.

2. **Integración con Open Library:** Al crear o actualizar un autor, el sistema automáticamente intenta buscar y agregar la `open_library_key` desde la API de Open Library basándose en el nombre del autor.

3. **Eliminación en cascada:** Al eliminar un autor, todos sus libros asociados también se eliminan automáticamente.

4. **Paginación:** Por defecto, sin el parámetro `with_pagination`, se retornan todos los autores. Con paginación activada, se muestran 10 autores por página (configurable con `per_page`).

5. **Relaciones eager loading:** El método `show` carga automáticamente todos los libros del autor usando eager loading para optimizar las consultas a la base de datos.

---

### Endpoints de Libros

Todas las rutas de libros requieren autenticación con Laravel Sanctum (middleware `auth:sanctum`).

**URL Base:** `/api/books`

#### 1. Listar todos los libros

Obtiene una colección de todos los libros, con soporte de paginación opcional.

**Endpoint:** `GET /api/books`

**Headers:**
```
Accept: application/json
```

**Query Parameters:**
- `with_pagination` (boolean, opcional): Si es `true`, retorna resultados paginados
- `per_page` (integer, opcional): Número de resultados por página (default: 10)

**Ejemplo sin paginación:**
```bash
curl -X GET "http://localhost:8000/api/books" \
  -H "Accept: application/json"
```

**Respuesta exitosa (200 OK):**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Cien años de soledad",
      "author_id": 1,
      "open_library_cover_key": "OL123456W",
      "first_publish_year": 1967,
      "units_available": 15,
      "created_at": "2025-10-20T10:30:00.000000Z",
      "updated_at": "2025-10-20T10:30:00.000000Z"
    },
    {
      "id": 2,
      "title": "El amor en los tiempos del cólera",
      "author_id": 1,
      "open_library_cover_key": "OL789012W",
      "first_publish_year": 1985,
      "units_available": 8,
      "created_at": "2025-10-20T10:31:00.000000Z",
      "updated_at": "2025-10-20T10:31:00.000000Z"
    }
  ]
}
```

**Ejemplo con paginación:**
```bash
curl -X GET "http://localhost:8000/api/books?with_pagination=true&per_page=20" \
  -H "Accept: application/json"
```

#### 2. Obtener un libro específico

Retorna la información de un libro.

**Endpoint:** `GET /api/books/{book_id}`

**Headers:**
```
Accept: application/json
```

**Ejemplo:**
```bash
curl -X GET "http://localhost:8000/api/books/1" \
  -H "Accept: application/json"
```

**Respuesta exitosa (200 OK):**
```json
{
  "data": {
    "id": 1,
    "title": "Cien años de soledad",
    "author_id": 1,
    "open_library_cover_key": "OL123456W",
    "first_publish_year": 1967,
    "units_available": 15,
    "created_at": "2025-10-20T10:30:00.000000Z",
    "updated_at": "2025-10-20T10:30:00.000000Z"
  }
}
```

#### 3. Crear un nuevo libro

Crea un nuevo libro en la base de datos asociado a un autor existente.

**Endpoint:** `POST /api/books/store`

**Headers:**
```
Accept: application/json
Content-Type: application/json
```

**Body (JSON):**
```json
{
  "author_id": 1,
  "title": "Crónica de una muerte anunciada",
  "first_publish_year": 1981,
  "units_available": 10
}
```

**Campos de validación:**
- `author_id` (integer, requerido): ID del autor (debe existir en la tabla authors)
- `title` (string, requerido, max: 255): Título del libro
- `first_publish_year` (integer, opcional, min: 1, max: año actual): Año de primera publicación
- `units_available` (integer, opcional, min: 0): Unidades disponibles para préstamo (default: 0)

**Ejemplo:**
```bash
curl -X POST "http://localhost:8000/api/books/store" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "author_id": 1,
    "title": "Crónica de una muerte anunciada",
    "first_publish_year": 1981,
    "units_available": 10
  }'
```

**Respuesta exitosa (201 Created):**
```json
{
  "message": "Libro creado exitosamente",
  "data": {
    "id": 151,
    "title": "Crónica de una muerte anunciada",
    "author_id": 1,
    "open_library_cover_key": null,
    "first_publish_year": 1981,
    "units_available": 10,
    "created_at": "2025-10-20T15:00:00.000000Z",
    "updated_at": "2025-10-20T15:00:00.000000Z"
  }
}
```

**Respuesta de error de validación (422 Unprocessable Entity):**
```json
{
  "message": "The author id field is required.",
  "errors": {
    "author_id": [
      "El autor es obligatorio."
    ],
    "title": [
      "El título del libro es obligatorio."
    ]
  }
}
```

**Respuesta de error del servidor (500 Internal Server Error):**
```json
{
  "message": "Error al crear el libro",
  "error": "Detalle del error..."
}
```

#### 4. Actualizar un libro existente

Actualiza la información de un libro. Permite actualizaciones parciales.

**Endpoint:** `PUT /api/books/update/{book_id}`

**Headers:**
```
Accept: application/json
Content-Type: application/json
```

**Body (JSON):**
```json
{
  "units_available": 20
}
```

**Ejemplo:**
```bash
curl -X PUT "http://localhost:8000/api/books/update/1" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "units_available": 20
  }'
```

**Respuesta exitosa (200 OK):**
```json
{
  "message": "Libro actualizado exitosamente",
  "data": {
    "id": 1,
    "title": "Cien años de soledad",
    "author_id": 1,
    "open_library_cover_key": "OL123456W",
    "first_publish_year": 1967,
    "units_available": 20,
    "created_at": "2025-10-20T10:30:00.000000Z",
    "updated_at": "2025-10-20T16:45:00.000000Z"
  }
}
```

**Respuesta de error (500 Internal Server Error):**
```json
{
  "message": "Error al actualizar el libro",
  "error": "Detalle del error..."
}
```

#### 5. Eliminar un libro

Elimina un libro de la base de datos. **Importante:** Si el libro tiene préstamos activos, también se eliminarán debido al `onDelete('cascade')`.

**Endpoint:** `DELETE /api/books/delete/{book_id}`

**Headers:**
```
Accept: application/json
```

**Ejemplo:**
```bash
curl -X DELETE "http://localhost:8000/api/books/delete/1" \
  -H "Accept: application/json"
```

**Respuesta exitosa (200 OK):**
```json
{
  "message": "Libro eliminado exitosamente"
}
```

### Endpoints de Usuarios

Todas las rutas de usuarios requieren autenticación con Laravel Sanctum (middleware `auth:sanctum`).

**URL Base:** `/api/users`

#### 1. Listar todos los usuarios

Obtiene una colección de todos los usuarios registrados, con soporte de paginación opcional.

**Endpoint:** `GET /api/users`

**Headers:**
```
Accept: application/json
```

**Query Parameters:**
- `with_pagination` (boolean, opcional): Si es `true`, retorna resultados paginados
- `per_page` (integer, opcional): Número de resultados por página (default: 10)

**Ejemplo sin paginación:**
```bash
curl -X GET "http://localhost:8000/api/users" \
  -H "Accept: application/json"
```

**Respuesta exitosa (200 OK):**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Juan Pérez",
      "email": "juan@ejemplo.com",
      "email_verified_at": null,
      "created_at": "2025-10-20T08:00:00.000000Z",
      "updated_at": "2025-10-20T08:00:00.000000Z"
    },
    {
      "id": 2,
      "name": "María González",
      "email": "maria@ejemplo.com",
      "email_verified_at": "2025-10-20T09:00:00.000000Z",
      "created_at": "2025-10-20T08:15:00.000000Z",
      "updated_at": "2025-10-20T09:00:00.000000Z"
    }
  ]
}
```

**Ejemplo con paginación:**
```bash
curl -X GET "http://localhost:8000/api/users?with_pagination=true&per_page=15" \
  -H "Accept: application/json"
```

### Endpoints de Préstamos

Todas las rutas de préstamos requieren autenticación con Laravel Sanctum (middleware `auth:sanctum`).

**URL Base:** `/api/loans`

#### 1. Registrar un nuevo préstamo

Registra un préstamo de libro. Valida que el libro tenga unidades disponibles y reduce automáticamente el inventario en 1 unidad.

**Endpoint:** `POST /api/loans/store`

**Headers:**
```
Accept: application/json
Content-Type: application/json
```

**Body (JSON):**
```json
{
  "user_id": 1,
  "book_id": 5,
  "date": "2025-10-20",
  "return_date": "2025-11-10"
}
```

**Campos de validación:**
- `user_id` (integer, requerido): ID del usuario (debe existir en la tabla users)
- `book_id` (integer, requerido): ID del libro (debe existir en la tabla books)
- `date` (date, requerido): Fecha del préstamo (formato: YYYY-MM-DD, no puede ser futura)
- `return_date` (date, requerido): Fecha de devolución prevista (debe ser posterior a `date` y no puede exceder 30 días de diferencia)

**Validaciones especiales:**
- La fecha de préstamo no puede ser futura (`before_or_equal:today`)
- La fecha de devolución debe ser posterior a la fecha de préstamo (`after:date`)
- La diferencia entre `date` y `return_date` no puede ser mayor a 30 días
- El libro debe tener al menos 1 unidad disponible

**Ejemplo:**
```bash
curl -X POST "http://localhost:8000/api/loans/store" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 1,
    "book_id": 5,
    "date": "2025-10-20",
    "return_date": "2025-11-10"
  }'
```

**Respuesta exitosa (201 Created):**
```json
{
  "message": "Prestamo registrado exitosamente",
  "data": {
    "id": 21,
    "user_id": 1,
    "book_id": 5,
    "date": "2025-10-20",
    "return_date": "2025-11-10",
    "created_at": "2025-10-20T15:30:00.000000Z",
    "updated_at": "2025-10-20T15:30:00.000000Z"
  }
}
```

**Respuesta cuando no hay unidades disponibles (200 OK):**
```json
{
  "message": "El libro no tiene unidades disponibles"
}
```

**Respuesta de error de validación (422 Unprocessable Entity):**
```json
{
  "message": "The date field is required.",
  "errors": {
    "user_id": [
      "El usuario es obligatorio."
    ],
    "book_id": [
      "El libro es obligatorio."
    ],
    "date": [
      "La fecha de préstamo es obligatoria."
    ],
    "return_date": [
      "La fecha de devolución no puede ser mayor a 30 días después de la fecha de préstamo."
    ]
  }
}
```

**Respuesta de error del servidor (500 Internal Server Error):**
```json
{
  "message": "Error al registrar el prestamo",
  "error": "Detalle del error..."
}
```

### Códigos de Estado HTTP

| Código | Significado | Cuándo se usa |
|--------|-------------|---------------|
| 200 | OK | Operación exitosa (GET, PUT, DELETE) |
| 201 | Created | Recurso creado exitosamente (POST) |
| 422 | Unprocessable Entity | Error de validación |
| 500 | Internal Server Error | Error del servidor |
| 401 | Unauthorized | Token de autenticación inválido o faltante |

### Notas Importantes

#### Libros
1. **Relación con autores:** Cada libro debe estar asociado a un autor existente. Si el autor es eliminado, sus libros también se eliminan (cascade).
2. **Integración con Open Library:** El sistema puede obtener automáticamente la `open_library_cover_key` desde la API de Open Library.
3. **Gestión de inventario:** El campo `units_available` se reduce automáticamente al registrar un préstamo.

#### Usuarios
1. **Solo lectura:** Por ahora, solo está disponible el endpoint de listado. La creación de usuarios se realiza mediante el endpoint `/api/register`.

#### Préstamos
1. **Validación de fechas:** El sistema valida estrictamente las fechas de préstamo y devolución, limitando los préstamos a un máximo de 30 días.
2. **Inventario automático:** Al registrar un préstamo, el sistema verifica disponibilidad y reduce automáticamente las `units_available` del libro en 1 unidad.
3. **Relaciones bidireccionales:** Los préstamos están relacionados tanto con usuarios como con libros mediante claves foráneas con cascade delete.

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
