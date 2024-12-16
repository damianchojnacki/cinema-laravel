<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Cinema (Laravel)

![Tests](https://github.com/damianchojnacki/cinema-laravel/actions/workflows/test.yml/badge.svg)

Cinema is a modern web application for browsing movies currently playing in theaters. The app offering a rich user experience with a seamless combination of backend and frontend technologies.
Check out [Symfony](https://github.com/damianchojnacki/cinema-symfony) version of this application.

## Features

- Browse movies playing in theaters with data fetched from the TMDB API.
- Responsive and user-friendly design powered by Next.js and TailwindCSS.
- Backend API built with Laravel.
- Component-based architecture using `@damianchojnacki/cinema` library.
- Fully containerized setup for development and production using Docker.
- Continuous Integration (CI) pipeline with GitHub Actions.

## Technologies Used

### Backend
- **PHP 8.3** with Symfony 6.
- **Laravel 11** for building a robust REST API.
- **PHPStan** and **PHPUnit** for static analysis and testing.
- **Laravel Pint** and **ESLint** for code linting.
- **FrankenPHP** and **Caddy** server.

### Frontend
- **Next.js** for server-rendered React applications.
- **TailwindCSS** for styling.
- **ESLint** for JavaScript/TypeScript linting.
- **Playwright** for end-to-end testing.
- **@damianchojnacki/cinema**: A reusable component library used across the application.

### Infrastructure
- **Laravel Sail** for development environment.
- **Docker** and **Docker Compose** for production environments.
- **GitHub Actions** for CI, running tests and linters.

## Project Structure

```plaintext
.
├── api/               # Symfony backend
├── pwa/               # Next.js frontend
├── e2e/               # E2E tests
```

## Setup Instructions

### Prerequisites

- Docker and Docker Compose installed.
- Laravel Sail alias - see [Laravel documentation](https://laravel.com/docs/11.x/sail#configuring-a-shell-alias)

### Development

1. **Clone the repository:**
   ```bash
   git clone https://github.com/damianchojnacki/cinema-laravel.git
   cd cinema
   ```

2. **Start the development environment:**
   ```bash
   cd api

   # This command is required only for the first initialization
   docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
   
   sail up -d
   ```

3. **Populate the database**
   ```bash
   sail artisan migrate
   sail artisan app:import-movies
   sail artisan db:seed
   ```

4. **Access the application:**
   - App: `http://localhost:3000`
   - API: `http://localhost`
   - API Docs: `http://localhost/docs/api`

### Testing

- **Backend tests:**
    ```bash
    sail test
    ```

- **Frontend tests:**
    ```bash
    docker run --network host -w /app -v ./e2e:/app --rm --ipc=host mcr.microsoft.com/playwright:v1.48.1-noble /bin/sh -c 'pnpm i; pnpm playwright test;'
    ```

- **Static analysis (PHPStan, ESLint):**
    ```bash
    sail php vendor/bin/phpstan --memory-limit=2G
    sail exec pwa pnpm lint
    ```

### Deploying to production

1. Fill the required env:
- SERVER_NAME
- APP_KEY
- APP_URL
- TMDB_API_KEY
- NEXT_PUBLIC_ENTRYPOINT

The API must be accessible during frontend build, so we need to build and start backend first.

2. **Build:**
    ```bash
    docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build --wait php && \
    docker compose -f docker-compose.yml -f docker-compose.prod.yml build pwa
    ```

3. **Start:**
    ```bash
    docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d --wait
    ```

### Note for deploying on Coolify

Let's assume that url address is https://cinema-laravel.damianchojnacki.com.

1. Create new Git project and set Docker Compose option for build pack.
2. In "Domains for Php" field type https://cinema-laravel.damianchojnacki.com:80.
3. Find a network name in Docker Compose content generated by Coolify and
   add it to the beginning of the build command:
    ```bash
    docker network create NETWORK_NAME && ...
    ```
   Fill Custom build command and custom start command with these from previous section.
4. Set Environment Variables:
- APP_URL -> https://cinema-laravel.damianchojnacki.com
- SERVER_NAME -> http://cinema-laravel.damianchojnacki.com
- APP_KEY -> XXX (see https://generate-random.org/laravel-key-generator)
- TMDB_API_KEY -> XXX (see https://developer.themoviedb.org/docs/getting-started)
5. If you are updating application you should stop currently running instance and click Deploy 🎉

## CI/CD

The project uses GitHub Actions for automated testing and linting:

- **Backend CI Pipeline:**
    - PHPStan for static analysis.
    - PHPUnit for unit tests.
    - Laravel Pint for code linting.

- **Frontend CI Pipeline:**
    - ESLint for linting.
    - Playwright for end-to-end tests.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
