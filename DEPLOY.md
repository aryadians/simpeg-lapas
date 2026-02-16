# Deployment Guide

This guide provides instructions for deploying **SIMPEG Lapas** to a production environment.

## Prerequisites

- A web server (Nginx or Apache)
- PHP >= 8.2 with required extensions (bcmath, ctype, fileinfo, json, mbstring, openssl, pdo, tokenizer, xml)
- MySQL, MariaDB, or PostgreSQL
- Composer
- Node.js & NPM

## Server Configuration

### Nginx Configuration Example

```nginx
server {
    listen 80;
    server_name simpeg.example.com;
    root /var/www/simpeg-lapas/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Deployment Steps

### 1. Upload Code
Clone the repository or upload your code to the server.

```bash
git clone https://github.com/aryadians/simpeg-lapas.git /var/www/simpeg-lapas
cd /var/www/simpeg-lapas
```

### 2. Install Dependencies
Run composer and npm to install production dependencies.

```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

### 3. Environment Setup
Copy the `.env.example` to `.env` and configure it.

```bash
cp .env.example .env
nano .env
```

**Crucial Production Settings:**
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://simpeg.example.com`
- `DB_DATABASE=...`
- `DB_USERNAME=...`
- `DB_PASSWORD=...`

Generate the application key:
```bash
php artisan key:generate --force
```

### 4. File Permissions
Ensure the `storage` and `bootstrap/cache` directories are writable by the web server.

```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### 5. Database Migration
Run the database migrations for the production environment.

```bash
php artisan migrate --force
```

### 6. Optimization
Laravel provides several commands to cache your configuration and routes for better performance.

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 7. Storage Link
Create the symbolic link for storage.

```bash
php artisan storage:link
```

## Maintenance

### Updating the Application
When updating the application, it's recommended to use maintenance mode:

```bash
php artisan down
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm install
npm run build
php artisan up
```

### Monitoring
Check the logs in `storage/logs/laravel.log` for any issues.
