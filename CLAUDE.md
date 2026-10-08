# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

University HCI (IHC) project: "Planazo", a simple plan creator on Laravel 12 (PHP ^8.2). It's a class project, not a production system.

## Coding style (user's explicit requirement)

Follow `apuntes de laravel.md` (the user's Laravel notes) — read it before writing code. In short:
- **Simple over secure/clever.** "Menos es mejor". Use Laravel's built-in tools (Auth, Password broker, `$request->validate`), no extra packages or abstractions.
- **Everything in Spanish**: file names, view names, routes, route names, methods, variables, form field names (`correo`, `contrasena`, `nombre`). Exceptions only where Laravel requires English: DB columns of `users` (`name`, `email`, `password`), route names `login` (auth middleware redirect) and `password.reset` (reset email link).
- Patterns from the notes: `Route::get/post(...)->name(...)` one per line, controllers that assign fields one by one (`$x = new Modelo(); $x->campo = $request->campo; $x->save();`), `compact()` to pass data to views, flash messages `->with('exito'|'error', ...)`, views using `@extends` a layout with `@yield('titulo')` / `@yield('contenido')`, partials in `layouts/_partials/`, CSS in `public/css/` via `asset()`.

## Commands

```bash
php artisan serve              # http://127.0.0.1:8000
php artisan migrate            # MySQL `proyecto2ihc` on 127.0.0.1:3306 (XAMPP, root)
php artisan route:list --except-vendor
php artisan test               # tests use in-memory SQLite (phpunit.xml)
php artisan test --filter=NombreDelTest
```

## Architecture

- `AutentificacionController` handles ingresar / registro / recuperar (sends reset email via `Password::sendResetLink`) / restablecer (`Password::reset`) / salir. Routes in `routes/web.php`; `/salir` and the planes routes are inside a `middleware('auth')` group. Login/registro redirect to `planes.listar`.
- `PlanesController` + `Plan` model (`$table = 'planes'`): CRUD of plans (nombre, organizador, fecha_limite, estado, user_id). `estado` is pendiente/confirmado/cancelado (default pendiente), changed via `cambiarEstado`; rule: a confirmed plan cannot be deleted (checked in `eliminar`, button disabled in the view). "Mis planes" (`/mis-planes`) lists only the logged-in user's plans; creating is a native `<dialog>` on that page, editing is a separate page.
- Views: auth pages use `layouts/acceso.blade.php`; app pages use `layouts/app.blade.php` (top bar + `@yield('estilos')`). Flash messages in `layouts/_partials/mensajes.blade.php`.
- CSS: one file per view in `public/css/` mirroring the view path (`planes/listar.blade.php` → `css/planes/listar.css`); shared styles in `css/app.css`, auth in `css/acceso.css`.
- Email: Gmail SMTP configured in `.env` (`MAIL_USERNAME` + Gmail app password). `APP_LOCALE=es`; `lang/es.json` translates Laravel's reset-password email.
- Unused leftovers: `Planes` and `Autentificacion` models, the `autentificacions` migration, `inicio.blade.php`.
