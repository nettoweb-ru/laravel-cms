# nettoweb CMS for Laravel

This software provides administrative section with basic functionality for a Laravel-based project.

## Complete installation from scratch

### Prepare MySQL database

Log into MySQL console with administrative privileges and run (replace all examples with actual values):

```mysql
# Create database
CREATE DATABASE `yourdomain_com` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Create user
CREATE USER 'yourdomain_com'@'localhost' IDENTIFIED BY 'password';

# Set up privileges
GRANT ALL PRIVILEGES ON `yourdomain_com`.* TO 'yourdomain_com'@'localhost';
FLUSH PRIVILEGES;
```

### Prepare filesystem

Change to projects directory and run (replace all examples with actual values):

```shell
# Create new Laravel 10 project
composer create-project laravel/laravel=10 yourdomain.com --prefer-dist

# Change to project directory
cd yourdomain.com

# Review and update these files
nano .env
nano config/app.php

# Install required packages
composer require nettoweb/laravel-cms

# Apply database migrations
php artisan migrate

# Publish assets
php artisan vendor:publish --tag=nettoweb-laravel-cms

# Be sure to set correct permissions to files and folders
find /var/www/yourdomain.com -type d -exec chmod 0755 {} \;
find /var/www/yourdomain.com -type f -exec chmod 0644 {} \;

# Set permissions to  Laravel directories
chmod -R 0777 /var/www/yourdomain.com/storage/
chmod -R 0775 /var/www/yourdomain.com/bootstrap/cache/

# Create storage links
php artisan storage:link
```

Use **admin@admin.com** and **password** to access the administrative section by opening the URL (replace *127.0.0.1* with actual value):
> http://127.0.0.1/admin

## Licensing

This project is licensed under MIT license.
