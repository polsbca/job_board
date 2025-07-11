# Job Board Application

A modern, full-stack job board built with **Laravel 12** and **PHP 8.2** that supports three distinct user roles:

* **Admin** – manage jobs, users, and monitor applications.
* **Employer** – post and manage job listings, review incoming applications.
* **Applicant** – browse jobs, apply, and track application status.

The application demonstrates clean architecture, role-based authorization, queue-based email notifications, and a SPA-like experience powered by Blade/Tailwind and Vite.

---

## Features

* Responsive landing page and public job listing
* Role-based dashboards (Admin, Employer, Applicant)
* CRUD for Jobs, Applications, and Users
* Authentication & Registration with Laravel Breeze
* Search & filtering for jobs
* File upload for résumés
* RESTful routing & policies
* Queue + Mail notifications on new applications
* Docker-friendly configuration

## Tech Stack

| Layer | Tech |
|-------|------|
| Backend | Laravel 12, PHP 8.2 |
| Frontend | Blade, Tailwind CSS, Vite |
| Auth | Laravel Sanctum |
| Database | MySQL / MariaDB / SQLite |
| Realtime | Pusher Channels |
| Testing | PHPUnit, Pest |

## Local Development

1. **Clone & install dependencies**

   ```bash
   git clone <repo-url> job_board && cd job_board
   composer install
   npm install
   ```

2. **Environment**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Update DB credentials in `.env`.

3. **Database**

   ```bash
   php artisan migrate --seed
   ```

4. **Run the dev servers** (hot-reloading & queues):

   ```bash
   php artisan serve   # API & backend
   npm run dev         # Vite + Tailwind
   php artisan queue:listen --tries=1
   ```

Visit `http://localhost:8000` to explore.

## Testing

```bash
php artisan test   # or: composer test
```

## Code of Conduct

We are committed to fostering a welcoming and inclusive environment. Please review and follow the [Laravel Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct) when contributing to this project.

## Security Vulnerabilities

If you discover a security vulnerability within this application or in one of its dependencies, please open an issue or e-mail the maintainer at **security@example.com**. All reports will be promptly addressed. For vulnerabilities specific to the Laravel framework, you may also contact [Taylor Otwell](mailto:taylor@laravel.com).

## License

This Job Board application is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


---

The remainder of this document contains the default Laravel README for reference.

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
