
> **Curso:** Ademass Capus | **Estado:** En proceso 🔨
> **Objetivo:** Dominar Laravel desde cero hasta APIs REST con Eloquent ORM

---

## 📚 Tabla de Contenidos

1. [[#⚙️ Instalación y Creación del Proyecto]]
2. [[#🛣️ Rutas (Routes)]]
3. [[#🖼️ Blade — Motor de Plantillas]]
4. [[#🗄️ Migraciones]]
5. [[#🧠 Modelos y Eloquent ORM]]
6. [[#🎮 Controladores]]
7. [[#🔄 Ciclo de Vida de un CRUD Completo]]
8. [[#📦 Request — El Mensajero HTTP]]
9. [[#✅ Validaciones]]
10. [[#🚨 Gestión de Errores y Mensajes Flash]]
11. [[#🔌 APIs REST con Laravel]]
12. [[#🌱 Seeders — Poblar la Base de Datos]]
13. [[#🏭 Factories — Datos Masivos]]
14. [[#🎭 Faker — Datos Falsos Realistas]]

---

## ⚙️ Instalación y Creación del Proyecto

### ¿Qué necesitás instalar?

Laravel necesita tres herramientas base en tu PC antes de poder crear un proyecto:

| Herramienta | Para qué sirve | Dónde descargarlo |
|---|---|---|
| **XAMPP** | Te da PHP + MySQL local | apachefriends.org |
| **Composer** | Manejador de paquetes de PHP (como npm pero para PHP) | getcomposer.org |
| **Node.js** | Laravel usa Vite para manejar CSS/JS | nodejs.org |

---

### 1. Configurar XAMPP para Laravel

Después de instalar XAMPP, hay que **habilitar extensiones** en el archivo `php.ini`:

```
C:\xampp\php\php.ini
```

Buscá estas líneas y **quitales el punto y coma** `;` del inicio (el `;` las desactiva):

```ini
; Antes (desactivadas):
;extension=pdo_mysql
;extension=zip

; Después (activadas):
extension=pdo_mysql
extension=zip
```

> 💡 **¿Por qué?** `pdo_mysql` permite que PHP hable con MySQL. `zip` lo necesita Composer para descargar paquetes.

---

### 2. Instalar Composer

Al instalar Composer, te va a preguntar dónde está tu `php.exe`. Le indicás:

```
C:\xampp\php\php.exe
```

---

### 3. Crear tu primer proyecto Laravel

**Paso 1** — Instalar el instalador de Laravel (solo una vez en tu PC):

```bash
composer global require laravel/installer
```

**Paso 2** — Ir a la carpeta donde querés el proyecto y crearlo:

```bash
laravel new mi-proyecto
```

> Durante la creación te va a preguntar si querés starter kits, base de datos, etc. Para empezar simple podés omitirlos.

---

### 4. Configurar el `.env`

El archivo `.env` en la raíz del proyecto es donde le decís a Laravel qué base de datos usar. Debe quedar así para XAMPP:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_de_tu_db
DB_USERNAME=root
DB_PASSWORD=
```

> 🔑 Si en phpMyAdmin configuraste una contraseña para root, ponela en `DB_PASSWORD`. Si no tiene contraseña (XAMPP por defecto), dejalo vacío.

---

### 5. Arrancar el servidor de desarrollo

```bash
# En una terminal:
php artisan serve       # Levanta el backend en http://127.0.0.1:8000

# En otra terminal (para CSS/JS con Vite):
npm run dev
```

---

## 🛣️ Rutas (Routes)

Las rutas son la **puerta de entrada** a tu aplicación. Cada URL que existe en tu sitio está definida acá. Se configuran en:

```
routes/web.php      ← Para páginas normales (HTML)
routes/api.php      ← Para APIs REST (JSON)
```

### El concepto clave

Cuando el navegador pide `miapp.com/usuarios`, Laravel busca en `web.php` si existe una ruta que coincida con `/usuarios` y ejecuta lo que le hayas asignado.

```php
use Illuminate\Support\Facades\Route;  // Siempre importar esto arriba
```

---

### Tipos de Rutas

#### Ruta Estática
Para páginas que nunca cambian (inicio, about, contacto). Devuelve directamente una vista:

```php
Route::view('/', 'welcome');
// URL: /  →  muestra la vista resources/views/welcome.blade.php
```

---

#### GET — Mostrar información

Se usa para **mostrar** páginas o datos. No modifica nada:

```php
Route::get('/usuarios', [UsuarioController::class, 'index']);
// URL: /usuarios  →  llama al método index() del UsuarioController
```

---

#### POST — Crear datos nuevos

Se usa cuando el usuario **envía un formulario** para crear algo:

```php
Route::post('/usuarios', [UsuarioController::class, 'store']);
// Recibe los datos del formulario y los guarda
```

---

#### PUT / PATCH — Actualizar datos

Se usa para **modificar** un registro existente:

```php
Route::put('/usuarios/{id}', [UsuarioController::class, 'update']);
// {id} es dinámico, le dice cuál usuario actualizar
```

---

#### DELETE — Eliminar datos

```php
Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']);
```

---

### Nombrar Rutas

Ponerle un **nombre** a una ruta es súper útil porque podés referirte a ella por nombre en lugar de escribir la URL completa. Si después cambiás la URL, no tenés que tocar el HTML:

```php
Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
```

En Blade la usás así:
```html
<a href="{{ route('usuarios.index') }}">Ver usuarios</a>
```

---

### Rutas Dinámicas

Cuando la URL tiene un **dato variable** (como un ID), se pone entre llaves `{}`:

```php
Route::get('/notas/{id}', [NotaController::class, 'show']);
//                 ^^^^  este valor viene de la URL
```

Y en el controlador lo recibís como parámetro:

```php
public function show($id) {
    $nota = Nota::find($id);
    return view('nota.show', compact('nota'));
}
```

---

### Rutas Resource (El Atajo del CRUD)

Si vas a hacer un CRUD completo, Laravel puede crear **todas las rutas automáticamente** con una sola línea:

```php
Route::resource('/notas', NotaController::class);
```

Esto crea automáticamente:

| Método HTTP | URL | Acción | Nombre de ruta |
|---|---|---|---|
| GET | /notas | index | notas.index |
| GET | /notas/create | create | notas.create |
| POST | /notas | store | notas.store |
| GET | /notas/{nota} | show | notas.show |
| GET | /notas/{nota}/edit | edit | notas.edit |
| PUT | /notas/{nota} | update | notas.update |
| DELETE | /notas/{nota} | destroy | notas.destroy |

> 🧠 Para que funcione, el controlador debe crearse con `--resource`:
> ```bash
> php artisan make:controller NotaController --resource
> ```

---

## 🖼️ Blade — Motor de Plantillas

Blade es el sistema de Laravel para escribir HTML + PHP de forma elegante. Los archivos Blade tienen extensión `.blade.php` y viven en:

```
resources/views/
```

### ¿Por qué Blade y no PHP puro?

PHP puro: `<?php echo $nombre; ?>`
Blade: `{{ $nombre }}`

Blade es más limpio, tiene directivas poderosas y **escapa automáticamente** el HTML para evitar ataques XSS.

---

### Layouts — La Plantilla Base

Un layout es como el **esqueleto HTML** de tu sitio. Define la estructura común (navbar, footer, etc.) y deja "huecos" para que otras vistas inyecten su contenido.

**Archivo base** `resources/views/layouts/app.blade.php`:

```html
<!DOCTYPE html>
<html>
<head>
    <title>@yield('titulo', 'Mi App')</title>
    {{-- @yield('nombre') define un hueco que otras vistas van a rellenar --}}
</head>
<body>
    <nav><!-- tu navbar aquí --></nav>

    <main>
        @yield('contenido')   {{-- Acá se inyectará el contenido de cada página --}}
    </main>

    <footer><!-- tu footer aquí --></footer>
</body>
</html>
```

**Vista hija** `resources/views/inicio.blade.php`:

```html
@extends('layouts.app')    {{-- "Voy a usar ese layout base" --}}

@section('titulo', 'Inicio')   {{-- Rellena el hueco 'titulo' --}}

@section('contenido')          {{-- Rellena el hueco 'contenido' --}}
    <h1>Bienvenido</h1>
    <p>Esta es la página de inicio.</p>
@endsection
```

> 💡 **Analogía:** El layout es como un molde de torta 🎂. Cada vista es la mezcla diferente que le ponés adentro.

---

### Parciales — Fragmentos Reutilizables

Los parciales son trozos de HTML que se repiten en varias páginas (navbar, footer, sidebar). Se guardan en:

```
resources/views/layouts/_partials/
```

**Crear** `_partials/navbar.blade.php`:
```html
<nav>
    <a href="{{ route('inicio') }}">Inicio</a>
    <a href="{{ route('notas.index') }}">Mis Notas</a>
</nav>
```

**Usar** en cualquier otra vista:
```html
@include('layouts._partials.navbar')
```

---

### Componentes — Parciales Inteligentes

Los componentes son como parciales pero **con variables**. Perfectos para elementos repetitivos que cambian su contenido (tarjetas de producto, alertas, botones).

Se guardan en `resources/views/_components/`.

**Crear** `_components/tarjeta.blade.php`:
```html
<div class="tarjeta">
    <h3>{{ $titulo }}</h3>
    <p>{{ $descripcion }}</p>
</div>
```

**Usar** con el contenido que quieras:
```html
@component('_components.tarjeta')
    @slot('titulo')
        Servicios de Programación
    @endslot

    @slot('descripcion')
        Tenemos todo tipo de productos para tu negocio.
    @endslot
@endcomponent
```

---

### Directivas Blade Esenciales

```html
{{-- Mostrar variable --}}
{{ $nombre }}

{{-- Condicionales --}}
@if ($usuario)
    <p>Bienvenido, {{ $usuario->nombre }}</p>
@elseif ($invitado)
    <p>Hola, invitado</p>
@else
    <p>No identificado</p>
@endif

{{-- Bucle foreach --}}
@foreach ($notas as $nota)
    <p>{{ $nota->titulo }}</p>
@endforeach

{{-- Verificar si está vacío --}}
@forelse ($notas as $nota)
    <p>{{ $nota->titulo }}</p>
@empty
    <p>No hay notas todavía.</p>
@endforelse

{{-- Comentarios (no se renderizan en HTML) --}}
{{-- Esto es un comentario Blade --}}
```

---

### Usar Rutas en HTML

Siempre usá `route()` en lugar de escribir URLs a mano:

```html
<ul>
    <li><a href="{{ route('inicio') }}">Inicio</a></li>
    <li><a href="{{ route('notas.show', $nota->id) }}">Ver nota</a></li>
    <li><a href="{{ route('notas.edit', $nota) }}">Editar</a></li>
</ul>
```

> ✅ **Ventaja:** Si cambiás la URL en `web.php`, todos los links se actualizan solos.

---

### Recursos Estáticos (Imágenes, CSS, JS)

Poné tus archivos en `public/`:
```
public/
  imagenes/
    logo.png
  css/
    estilos.css
```

Usarlos en Blade:
```html
<img src="{{ asset('imagenes/logo.png') }}" width="200">
<link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
```

> `asset()` genera la URL correcta sin importar en qué servidor estés.

---

## 🗄️ Migraciones

En Laravel **no creás tablas con MySQL directamente**. En cambio, usás migraciones: archivos PHP que describen cómo debe ser tu base de datos. Esto tiene varias ventajas:

- ✅ Podés hacer "control Z" a tu base de datos
- ✅ Todo el equipo tiene la misma estructura
- ✅ Podés recrear la BD desde cero en cualquier momento

Las migraciones viven en: `database/migrations/`

---

### Comandos Esenciales

```bash
# Crear una nueva migración
php artisan make:migration crear_notas_table

# Ejecutar migraciones (crea las tablas físicamente en la BD)
php artisan migrate

# Deshacer la última migración (el "Ctrl+Z" de la BD)
php artisan migrate:rollback

# Borrar todo y recrear desde cero (¡CUIDADO! Borra todos los datos)
php artisan migrate:fresh

# Recrear y ejecutar seeders al mismo tiempo
php artisan migrate:fresh --seed
```

---

### Anatomía de una Migración

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * UP: Define cómo crear la tabla
     * Se ejecuta con: php artisan migrate
     */
    public function up(): void
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->id();                        // ID autoincremental (llave primaria)
            $table->string('titulo', 255);        // VARCHAR(255) NOT NULL
            $table->text('descripcion');          // TEXT NOT NULL
            $table->integer('orden');             // INT NOT NULL
            $table->boolean('activo')->default(true);  // Con valor por defecto
            $table->string('estado')->nullable();  // Puede ser NULL
            $table->enum('prioridad', ['alta', 'media', 'baja']);  // Solo esos valores
            $table->timestamps();                // Crea created_at y updated_at
        });
    }

    /**
     * DOWN: Define cómo revertir (Ctrl+Z)
     * Se ejecuta con: php artisan migrate:rollback
     */
    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
```

---

### Tipos de Columnas Más Usados

| Método | Tipo SQL | Ejemplo |
|---|---|---|
| `$table->id()` | BIGINT UNSIGNED AI | Llave primaria |
| `$table->string('nombre', 255)` | VARCHAR(255) | Nombre, email |
| `$table->text('contenido')` | TEXT | Descripciones largas |
| `$table->integer('cantidad')` | INT | Números enteros |
| `$table->decimal('precio', 8, 2)` | DECIMAL(8,2) | Precios |
| `$table->boolean('activo')` | TINYINT(1) | true/false |
| `$table->date('nacimiento')` | DATE | Fechas |
| `$table->timestamp('publicado_en')` | TIMESTAMP | Fecha y hora |
| `$table->enum('rol', ['admin','user'])` | ENUM | Opciones fijas |
| `$table->foreignId('user_id')` | BIGINT UNSIGNED | Llave foránea |
| `$table->timestamps()` | created_at + updated_at | Automático |

---

### Modificadores de Columnas

```php
->nullable()           // Permite valores NULL
->default('valor')     // Valor por defecto
->unique()             // No permite duplicados
->unsigned()           // Solo números positivos
->after('columna')     // Posición después de otra columna
```

---

## 🧠 Modelos y Eloquent ORM

El modelo es una **clase PHP que representa una tabla**. Eloquent ORM te permite hablar con la base de datos usando **objetos PHP** en lugar de SQL puro.

> 🧠 **Analogía:** Si la migración es el plano de la tabla, el Modelo es el empleado que sabe cómo trabajar con esa tabla.

```bash
# Crear solo el modelo
php artisan make:model Nota

# ⭐ SUPERCOMANDO: Crea Modelo + Migración + Controlador de una vez
php artisan make:model Nota -mcr
```

> Por convención: el modelo siempre en **singular y PascalCase** (`Nota`, `Usuario`, `ProductoVenta`). Laravel busca automáticamente la tabla en plural (`notas`, `usuarios`, `producto_ventas`).

---

### Configuración del Modelo

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    /**
     * Lista BLANCA: campos que el usuario PUEDE llenar masivamente
     * (con Nota::create([...]) o $nota->update([...]))
     */
    protected $fillable = [
        'titulo',
        'descripcion',
        'orden',
    ];

    /**
     * Lista NEGRA: campos PROTEGIDOS (nunca los puede llenar el usuario)
     * Usá $guarded O $fillable, no los dos
     */
    // protected $guarded = ['id'];

    /**
     * Campos que NO aparecen al convertir a JSON
     * Ideal para contraseñas, tokens de API, etc.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
```

---

### Métodos Estáticos — Consultas a la Tabla

Son métodos que actúan sobre **toda la tabla** (no sobre un registro específico):

```php
// Traer TODOS los registros
$notas = Nota::all();

// Buscar por ID
$nota = Nota::find(5);          // Retorna null si no existe
$nota = Nota::findOrFail(5);    // Lanza error 404 si no existe ← Más seguro

// Filtrar con condición (WHERE en SQL)
$notas = Nota::where('activo', true)->get();
$notas = Nota::where('prioridad', 'alta')->where('activo', true)->get();
$notas = Nota::where('orden', '>', 3)->get();

// Ordenar
$notas = Nota::orderBy('created_at', 'desc')->get();

// Solo el primero
$nota = Nota::where('titulo', 'Mi nota')->first();

// Crear y guardar en un paso (necesita $fillable en el modelo)
$nota = Nota::create([
    'titulo' => 'Nueva nota',
    'descripcion' => 'Descripción de la nota',
]);

// Contar registros
$total = Nota::count();

// Encadenar todo
$notas = Nota::where('activo', true)
             ->orderBy('orden', 'asc')
             ->get();
```

---

### Métodos de Instancia — Actúan sobre UN Registro

Son métodos que usás cuando tenés **un objeto específico** en mano:

```php
// Guardar cambios (funciona para INSERT y UPDATE)
$nota = new Nota();
$nota->titulo = 'Mi nota';
$nota->descripcion = 'Descripción';
$nota->save();              // INSERT

$nota->titulo = 'Título actualizado';
$nota->save();              // UPDATE

// Actualizar varios campos a la vez
$nota->update([
    'titulo' => 'Nuevo título',
    'descripcion' => 'Nueva descripción',
]);

// Eliminar el registro
$nota->delete();

// Convertir a JSON (para APIs)
$json = $nota->toJson();

// Verificar si una colección está vacía
if ($notas->isEmpty()) {
    echo "No hay notas";
}
```

---

## 🎮 Controladores

El controlador es el **director de orquesta**: recibe la petición de la ruta, le pide datos al modelo, y los envía a la vista. La lógica de negocio vive acá.

```bash
php artisan make:controller NotaController
php artisan make:controller NotaController --resource  # Con todos los métodos CRUD
```

**Estructura básica:**

```php
<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use Illuminate\Http\Request;

class NotaController extends Controller
{
    public function index()
    {
        $notas = Nota::all();
        return view('notas.index', compact('notas'));
        //                          ↑ compact() convierte variables locales
        //                            en array para la vista
    }
}
```

> 💡 **`compact('notas')`** es equivalente a `['notas' => $notas]`. Es un atajo de PHP.

---

### Vincular con la Ruta

```php
// En web.php:
Route::get('/notas', [NotaController::class, 'index'])->name('notas.index');

// Siempre importar el controlador arriba del archivo:
use App\Http\Controllers\NotaController;
```

---

## 🔄 Ciclo de Vida de un CRUD Completo

Este es el patrón que vas a repetir en **todos** tus proyectos. Aprendételo bien.

### INDEX — Listar todos los registros

**Ruta:**
```php
Route::get('/notas', [NotaController::class, 'index'])->name('notas.index');
```

**Controlador:**
```php
public function index()
{
    $notas = Nota::orderBy('created_at', 'desc')->get();
    return view('notas.index', compact('notas'));
}
```

**Vista** `resources/views/notas/index.blade.php`:
```html
@extends('layouts.app')

@section('contenido')
<h1>Mis Notas</h1>
<a href="{{ route('notas.create') }}">+ Nueva Nota</a>

<table>
    <thead>
        <tr>
            <th>Título</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($notas as $nota)
        <tr>
            <td>{{ $nota->titulo }}</td>
            <td>{{ $nota->descripcion }}</td>
            <td>
                <a href="{{ route('notas.edit', $nota) }}">Editar</a>

                {{-- Formulario para DELETE (los navegadores solo soportan GET y POST) --}}
                <form method="POST" action="{{ route('notas.destroy', $nota) }}">
                    @csrf
                    @method('DELETE')   {{-- Le dice a Laravel que es DELETE --}}
                    <button type="submit" onclick="return confirm('¿Eliminar?')">
                        Eliminar
                    </button>
                </form>
            </td>
        </tr>
        @empty
            <tr><td colspan="3">No hay notas todavía.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
```

---

### CREATE y STORE — Mostrar formulario y guardar

**Rutas:**
```php
Route::get('/notas/crear', [NotaController::class, 'create'])->name('notas.create');
Route::post('/notas', [NotaController::class, 'store'])->name('notas.store');
```

**Controlador:**
```php
// Muestra el formulario vacío
public function create()
{
    return view('notas.create');
}

// Procesa el formulario y guarda en BD
public function store(Request $request)
{
    $nota = new Nota();
    $nota->titulo = $request->titulo;
    $nota->descripcion = $request->descripcion;
    $nota->save();

    return redirect()->route('notas.index')
                     ->with('exito', '¡Nota creada correctamente!');
}
```

**Vista** `resources/views/notas/create.blade.php`:
```html
@extends('layouts.app')

@section('contenido')
<h1>Nueva Nota</h1>

<form method="POST" action="{{ route('notas.store') }}">
    @csrf  {{-- Token de seguridad OBLIGATORIO en todos los formularios POST --}}

    <label>Título:</label>
    <input type="text" name="titulo" value="{{ old('titulo') }}">
    {{-- old('titulo') recupera el valor si hubo un error de validación --}}
    @error('titulo')
        <span style="color:red">{{ $message }}</span>
    @enderror

    <label>Descripción:</label>
    <textarea name="descripcion">{{ old('descripcion') }}</textarea>
    @error('descripcion')
        <span style="color:red">{{ $message }}</span>
    @enderror

    <button type="submit">Guardar</button>
    <a href="{{ route('notas.index') }}">Cancelar</a>
</form>
@endsection
```

---

### SHOW — Ver un solo registro

**Ruta:**
```php
Route::get('/notas/{nota}', [NotaController::class, 'show'])->name('notas.show');
```

**Controlador** (con Route Model Binding 🔥):
```php
// Laravel busca automáticamente la Nota con ese ID — ¡sin find() manual!
public function show(Nota $nota)
{
    return view('notas.show', compact('nota'));
}
```

> 🔥 **Route Model Binding:** Si el parámetro de la ruta (`{nota}`) coincide con el nombre del parámetro del método (`Nota $nota`), Laravel hace el `find()` automáticamente. Si no existe, devuelve 404.

---

### EDIT y UPDATE — Editar y actualizar

**Rutas:**
```php
Route::get('/notas/{nota}/edit', [NotaController::class, 'edit'])->name('notas.edit');
Route::put('/notas/{nota}', [NotaController::class, 'update'])->name('notas.update');
```

**Controlador:**
```php
// Muestra el formulario con los datos actuales
public function edit(Nota $nota)
{
    return view('notas.edit', compact('nota'));
}

// Guarda los cambios
public function update(Request $request, Nota $nota)
{
    $nota->titulo = $request->titulo;
    $nota->descripcion = $request->descripcion;
    $nota->save();

    return redirect()->route('notas.index')
                     ->with('exito', '¡Nota actualizada!');
}
```

**Vista** `resources/views/notas/edit.blade.php`:
```html
<form method="POST" action="{{ route('notas.update', $nota) }}">
    @csrf
    @method('PUT')   {{-- Los navegadores no soportan PUT, esto lo simula --}}

    <input type="text" name="titulo" value="{{ $nota->titulo }}">
    <textarea name="descripcion">{{ $nota->descripcion }}</textarea>

    <button type="submit">Actualizar</button>
</form>
```

---

### DESTROY — Eliminar

**Ruta:**
```php
Route::delete('/notas/{nota}', [NotaController::class, 'destroy'])->name('notas.destroy');
```

**Controlador:**
```php
public function destroy(Nota $nota)
{
    $nota->delete();
    return redirect()->route('notas.index')
                     ->with('exito', 'Nota eliminada.');
}
```

---

## 📦 Request — El Mensajero HTTP

`Request` es la clase que **empaqueta todo lo que el usuario envió** al hacer submit en un formulario. Es como una mochila con todos los datos.

### ¿Cómo llegan los datos al controlador?

```
Usuario llena formulario
    → Click en "Enviar"
        → Navegador empaqueta los datos (body HTTP)
            → Laravel los mete en el objeto $request
                → Tu controlador recibe $request ya cargado
```

### En el controlador:

```php
use Illuminate\Http\Request;

public function store(Request $request)
{
    // Métodos de instancia más usados:
    $todos = $request->all();           // Array con todo lo enviado
    $titulo = $request->input('titulo'); // Un campo específico (más seguro)
    $existe = $request->has('titulo');   // true/false si existe el campo
    $archivo = $request->file('foto');   // Para archivos subidos

    // Métodos estáticos (menos usados):
    $ip = Request::ip();                 // IP del visitante
    $metodo = Request::method();         // 'GET', 'POST', 'PUT', etc.
}
```

### Los 3 ingredientes del formulario

```html
<form method="POST" action="{{ route('notas.store') }}">
<!--  ↑ "method" le dice al navegador cómo empaquetar -->

    @csrf
    <!--  ↑ Token de seguridad. Sin esto Laravel rechaza el formulario -->

    <input type="text" name="titulo">
    <!--                ↑ "name" es la llave en $request. Sin esto, el input se ignora -->

    <button type="submit">Enviar</button>
    <!--  ↑ El gatillo que dispara todo -->
</form>
```

---

## ✅ Validaciones

Antes de guardar datos en la BD, siempre validá que sean correctos. Laravel tiene un sistema de validación muy potente.

### Opción 1: Validación en el Controlador

```php
public function store(Request $request)
{
    // Si algo falla, Laravel redirige al formulario con los errores automáticamente
    $request->validate([
        'titulo'      => 'required|max:255',
        'descripcion' => 'required|min:5|string',
        'email'       => 'required|email|unique:users',
        'edad'        => 'required|integer|min:18|max:99',
        'foto'        => 'nullable|image|max:2048',  // Imagen, máx 2MB
    ]);

    // Si llega hasta acá, los datos son válidos ✅
    $nota = new Nota();
    $nota->titulo = $request->titulo;
    $nota->descripcion = $request->descripcion;
    $nota->save();

    return redirect()->route('notas.index');
}
```

---

### Reglas de Validación más Usadas

| Regla | Significado |
|---|---|
| `required` | Campo obligatorio |
| `nullable` | Puede ser vacío/nulo |
| `string` | Debe ser texto |
| `integer` | Debe ser número entero |
| `numeric` | Número (int o decimal) |
| `email` | Formato email válido |
| `min:5` | Mínimo 5 caracteres (o valor) |
| `max:255` | Máximo 255 caracteres (o valor) |
| `unique:tabla` | No puede repetirse en esa tabla |
| `exists:tabla,columna` | Debe existir en esa tabla |
| `image` | Debe ser imagen (jpg, png, etc.) |
| `in:val1,val2` | Solo esos valores permitidos |
| `confirmed` | Debe coincidir con `campo_confirmation` |

---

### Opción 2: Custom Request (Para Proyectos Grandes)

Cuando tenés muchas validaciones repetidas en varios controladores, creás una clase aparte:

```bash
php artisan make:request NotaRequest
# Crea: App/Http/Requests/NotaRequest.php
```

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotaRequest extends FormRequest
{
    // ¿Quién puede hacer esta petición?
    public function authorize(): bool
    {
        return true;  // true = todos pueden; false = nadie puede
    }

    // Las reglas de validación
    public function rules(): array
    {
        return [
            'titulo'      => 'required|max:255',
            'descripcion' => 'required|min:5',
        ];
    }

    // Mensajes de error personalizados (opcional)
    public function messages(): array
    {
        return [
            'titulo.required' => 'El título es obligatorio.',
            'titulo.max'      => 'El título no puede tener más de 255 caracteres.',
        ];
    }
}
```

**Usar en el controlador** (importando la clase):

```php
use App\Http\Requests\NotaRequest;

// En lugar de Request $request, ponés NotaRequest $request
public function store(NotaRequest $request)
{
    // La validación ya se ejecutó automáticamente ✅
    $nota = new Nota();
    $nota->titulo = $request->titulo;
    $nota->descripcion = $request->descripcion;
    $nota->save();

    return redirect()->route('notas.index');
}
```

---

## 🚨 Gestión de Errores y Mensajes Flash

### Mostrar Errores en la Vista

```html
{{-- Error para un campo específico --}}
<input type="text" name="titulo" value="{{ old('titulo') }}"
       class="@error('titulo') input-error @enderror">
                 {{-- Agrega clase CSS si hay error en 'titulo' --}}

@error('titulo')
    <p class="error-msg">{{ $message }}</p>
@enderror

{{-- Mostrar TODOS los errores juntos --}}
@if ($errors->any())
    <div class="alert-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
```

---

### Mensajes Flash — El Post-it de una Sola Vez

Los mensajes flash son **mensajes temporales** que duran una sola carga de página. Perfectos para confirmaciones de éxito/error.

**Enviar desde el controlador:**
```php
return redirect()->route('notas.index')
                 ->with('exito', '¡Nota guardada correctamente!');
                 ->with('error', 'Hubo un problema.');
```

**Mostrar en la vista:**
```html
@if (session('exito'))
    <div class="alert-success">
        {{ session('exito') }}
    </div>
@endif

@if (session('error'))
    <div class="alert-error">
        {{ session('error') }}
    </div>
@endif
```

**Lo ideal:** crear un partial `_partials/mensajes.blade.php` con ese código y usarlo con `@include` en el layout base para no repetirlo.

---

## 🔌 APIs REST con Laravel

Cuando construís una **API**, no devolvés vistas HTML, sino **JSON**. Esto es lo que consume tu frontend en Angular, React, Flutter, etc.

> 📐 **Diferencia clave:**
> - **Monolito** = Backend + HTML juntos (Blade) → Para webs tradicionales
> - **API REST** = Solo JSON → Para cuando el frontend es separado (Angular, Flutter, React Native)

---

### Configurar las Rutas de API

Las rutas de API van en `api.php`. Para crearlo:

```bash
php artisan install:api
```

Las rutas en `api.php` **automáticamente tienen el prefijo `/api/`**:

```php
// En api.php:
Route::get('/notas', [NotaController::class, 'index']);
// URL real: http://localhost:8000/api/notas
```

---

### Controlador para API

```php
<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use Illuminate\Http\Request;

class NotaController extends Controller
{
    // GET /api/notas — Listar todas
    public function index()
    {
        $notas = Nota::all();
        return response()->json($notas, 200);
    }

    // POST /api/notas — Crear nueva
    public function store(Request $request)
    {
        $nota = new Nota();
        $nota->titulo = $request->titulo;
        $nota->descripcion = $request->descripcion;
        $nota->save();

        return response()->json([
            'exito' => true,
            'data'  => $nota,
            'mensaje' => 'Nota creada correctamente'
        ], 201);  // 201 = Created
    }

    // GET /api/notas/{nota} — Ver una
    public function show(Nota $nota)
    {
        return response()->json($nota, 200);
    }

    // PUT /api/notas/{nota} — Actualizar
    public function update(Request $request, Nota $nota)
    {
        $nota->titulo = $request->titulo;
        $nota->descripcion = $request->descripcion;
        $nota->save();

        return response()->json([
            'exito' => true,
            'data'  => $nota
        ], 200);
    }

    // DELETE /api/notas/{nota} — Eliminar
    public function destroy(Nota $nota)
    {
        $nota->delete();
        return response()->json(['exito' => true], 200);
    }
}
```

---

### Códigos HTTP más Usados

| Código | Significado | Cuándo usarlo |
|---|---|---|
| 200 | OK | Éxito general, GET exitoso |
| 201 | Created | Recurso creado (POST exitoso) |
| 204 | No Content | Éxito sin respuesta (DELETE) |
| 400 | Bad Request | Datos inválidos |
| 401 | Unauthorized | No autenticado |
| 403 | Forbidden | Sin permisos |
| 404 | Not Found | Recurso no existe |
| 422 | Unprocessable | Error de validación |
| 500 | Server Error | Error del servidor |

---

### Probar la API

Usá **Thunder Client** (extensión de VS Code) o **Postman**:

```
GET    http://localhost:8000/api/notas
POST   http://localhost:8000/api/notas
GET    http://localhost:8000/api/notas/1
PUT    http://localhost:8000/api/notas/1
DELETE http://localhost:8000/api/notas/1
```

**Body JSON para POST/PUT:**
```json
{
    "titulo": "Mi primera nota vía API",
    "descripcion": "Creada con Thunder Client"
}
```

---

## 🌱 Seeders — Poblar la Base de Datos

Los seeders sirven para **insertar datos iniciales** necesarios en el sistema: el usuario administrador, categorías por defecto, configuraciones, etc.

```bash
# Crear un seeder
php artisan make:seeder NotaSeeder
# Crea: database/seeders/NotaSeeder.php
```

---

### Registrar el Seeder

Primero hay que decirle a Laravel que existe. En `database/seeders/DatabaseSeeder.php`:

```php
public function run(): void
{
    $this->call([
        NotaSeeder::class,
        UsuarioSeeder::class,
        CategoriaSeeder::class,
    ]);
}
```

---

### Escribir el Seeder

```php
<?php

namespace Database\Seeders;

use App\Models\Nota;
use Illuminate\Database\Seeder;

class NotaSeeder extends Seeder
{
    public function run(): void
    {
        // Forma 1: Crear de uno en uno
        Nota::create([
            'titulo' => 'Primera nota',
            'descripcion' => 'Esta es la primera nota del sistema',
        ]);

        // Forma 2: Array + foreach (más limpio para varios)
        $notas = [
            ['titulo' => 'Nota de bienvenida', 'descripcion' => 'Bienvenido al sistema'],
            ['titulo' => 'Cómo empezar',        'descripcion' => 'Lee esta nota primero'],
            ['titulo' => 'Contacto',             'descripcion' => 'Escríbenos a info@app.com'],
        ];

        foreach ($notas as $nota) {
            Nota::create($nota);
        }
    }
}
```

> ⚠️ Para que `Nota::create([...])` funcione, los campos deben estar en `$fillable` del modelo.

---

### Ejecutar los Seeders

```bash
# Solo ejecutar seeders (sin borrar datos)
php artisan db:seed

# Ejecutar un seeder específico
php artisan db:seed --class=NotaSeeder

# ⭐ Resetear BD y ejecutar todos los seeders (desarrollo)
php artisan migrate:fresh --seed
```

---

## 🏭 Factories — Datos Masivos para Pruebas

Las factories sirven para generar **muchos datos de prueba** automáticamente. Perfectas para probar paginación, búsquedas, performance.

```bash
php artisan make:factory NotaFactory
# Crea: database/factories/NotaFactory.php
```

---

### Crear la Factory

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NotaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'titulo'      => $this->faker->sentence(4),         // "Lorem ipsum dolor sit"
            'descripcion' => $this->faker->paragraph(3),        // Párrafo de 3 oraciones
            'activo'      => $this->faker->boolean(80),         // 80% true, 20% false
            'orden'       => $this->faker->numberBetween(1, 10),
        ];
    }
}
```

---

### Usar la Factory en un Seeder

```php
public function run(): void
{
    // Crear 50 notas con datos falsos
    Nota::factory()->count(50)->create();

    // Crear con algún campo fijo
    Nota::factory()->count(10)->create([
        'activo' => true,
    ]);
}
```

---

### Vincular Factory al Modelo

En el modelo `Nota.php`, agregá el trait `HasFactory`:

```php
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Nota extends Model
{
    use HasFactory;
    // ...
}
```

---

## 🎭 Faker — Datos Falsos Realistas

Faker es la librería que usa Laravel para generar datos falsos. Se accede desde `$this->faker` dentro de una Factory.

### Métodos de Faker más Útiles

```php
// Textos
$this->faker->word()                    // "lorem"
$this->faker->sentence(5)               // "Lorem ipsum dolor sit amet."
$this->faker->sentences(3, true)        // String con 3 oraciones
$this->faker->paragraph(2)              // Párrafo de 2 oraciones
$this->faker->text(200)                 // Texto de ~200 caracteres

// Personas
$this->faker->name()                    // "María García"
$this->faker->firstName()               // "Carlos"
$this->faker->lastName()                // "Rodríguez"
$this->faker->email()                   // "carlos@example.com"
$this->faker->password()                // "YkW#8xP2"
$this->faker->phoneNumber()             // "+591 77812345"

// Números
$this->faker->randomNumber(5)           // 34821
$this->faker->numberBetween(1, 100)     // 47
$this->faker->randomFloat(2, 10, 500)   // 234.56 (2 decimales, entre 10 y 500)

// Booleans
$this->faker->boolean()                 // true o false
$this->faker->boolean(70)               // 70% chance de true

// Fechas
$this->faker->date()                    // "2024-03-15"
$this->faker->dateTimeBetween('-1 year', 'now')  // Fecha del último año

// Internet
$this->faker->url()                     // "https://example.com/path"
$this->faker->imageUrl(640, 480)        // URL de imagen placeholder

// Dirección
$this->faker->city()                    // "Santa Cruz"
$this->faker->address()                 // Dirección completa

// Opciones aleatorias
$this->faker->randomElement(['rojo', 'verde', 'azul'])  // Uno al azar
$this->faker->unique()->numberBetween(1, 1000)          // Sin repetir
```

### Usar Faker en Español

En `config/app.php`:
```php
'faker_locale' => 'es_ES',
```

---

## 📌 Referencia Rápida — Comandos Artisan

```bash
# ---- CREAR COSAS ----
php artisan make:model Nota                    # Modelo
php artisan make:model Nota -mcr               # Modelo + Migración + Controlador resource
php artisan make:controller NotaController     # Controlador básico
php artisan make:controller NotaController --resource  # Con métodos CRUD
php artisan make:migration crear_notas_table   # Migración
php artisan make:seeder NotaSeeder             # Seeder
php artisan make:factory NotaFactory           # Factory
php artisan make:request NotaRequest           # Form Request

# ---- BASE DE DATOS ----
php artisan migrate                            # Ejecutar migraciones
php artisan migrate:rollback                   # Deshacer última migración
php artisan migrate:fresh                      # Borrar todo y recrear
php artisan migrate:fresh --seed               # Recrear + poblar con seeders
php artisan db:seed                            # Solo ejecutar seeders
php artisan db:seed --class=NotaSeeder         # Seeder específico

# ---- SERVIDOR ----
php artisan serve                              # Levantar servidor en :8000
php artisan tinker                             # REPL interactivo (para probar)

# ---- UTILIDADES ----
php artisan route:list                         # Ver todas las rutas registradas
php artisan cache:clear                        # Limpiar caché
php artisan config:clear                       # Limpiar caché de configuración
php artisan optimize:clear                     # Limpiar todo
```

---

## 🗺️ Arquitectura MVC en Laravel

```
REQUEST (navegador/app)
    ↓
RUTAS (web.php / api.php)
    ↓ define qué controlador usar
CONTROLADOR (app/Http/Controllers/)
    ↓ pide datos al modelo
MODELO (app/Models/)
    ↓ consulta la BD con Eloquent
BASE DE DATOS (MySQL via .env)
    ↑ devuelve los datos
MODELO → CONTROLADOR
    ↓ envía datos a la vista
VISTA (resources/views/) — Solo en monolito
    ↓ HTML renderizado
RESPONSE (navegador)

--- En APIs:
MODELO → CONTROLADOR
    ↓ sin vista
response()->json($data, 200)
    ↓ JSON puro
FRONTEND (Angular / Flutter / React)
```

---

*Apunte generado con base en el curso de Ademass Capus y expandido con ejemplos propios.*
*Última actualización: Abril 2026*
