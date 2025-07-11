# Contributing Guide

Thanks for taking the time to contribute! This document describes the workflow and conventions for this project.

---

## Table of Contents

1. Getting Started
2. Branch Strategy
3. Coding Standards
4. Commit Messages
5. Pull Requests
6. Running Tests
7. Static Analysis & Linting
8. Security Policy

---

## 1. Getting Started

1. Fork the repository and clone your fork:
   ```bash
   git clone https://github.com/<your-username>/job_board.git && cd job_board
   ```
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```
3. Copy the example environment and generate an application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Run the migrations & seeders:
   ```bash
   php artisan migrate --seed
   ```
5. Start the dev servers:
   ```bash
   npm run dev   # Vite
   php artisan serve
   ```

---

## 2. Branch Strategy

* **main** – always deployable / production-ready.
* **develop** – integration branch for features; may be unstable.
* **feature/**`short-description` – new features or large refactors.
* **bugfix/**`short-description` – bug fixes.
* **hotfix/**`short-description` – critical production fixes.

> Use kebab-case for branch names.

---

## 3. Coding Standards

* **PHP:** Follow **PSR-12**. Auto-format with `composer pint`.
* **JavaScript / Blade:** Use **Prettier** (configured in `package.json`).
* **Commit only formatted code.** A pre-commit hook is provided via Husky to run Pint & Prettier.

---

## 4. Commit Messages

Use the [Conventional Commits](https://www.conventionalcommits.org) style:

```
<type>(scope?): <subject>

<body>
```

Example:
```
feat(auth): add employer email verification
```

Allowed `<type>` values: `feat`, `fix`, `docs`, `refactor`, `test`, `chore`.

---

## 5. Pull Requests

1. Rebase onto `develop` before opening the PR.
2. Ensure the CI pipeline passes (tests + lint).
3. Provide a clear description, screenshots/GIFs, and link related issues.
4. At least one reviewer approval is required.

---

## 6. Running Tests

```bash
php artisan test
npm run test   # if frontend tests are added
```

Generate coverage:
```bash
php artisan test --coverage-html=storage/coverage
```

---

## 7. Static Analysis & Linting

```bash
composer pint   # PHP formatter / linter
npm run lint    # ESLint / Prettier
```

---

## 8. Security Policy

Report vulnerabilities by email to **security@example.com** or open a private GitHub security advisory. Do **not** create public issues for security problems.

---

Happy coding! 🎉
