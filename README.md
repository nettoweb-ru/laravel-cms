# nettoweb CMS for Laravel

This software provides administrative section with basic functionality for a Laravel-based project.

## Installation

Change to your Laravel project directory and run: 

```shell
composer require nettoweb/laravel-cms
```

Apply database migrations:

```shell
php artisan migrate
```
Publish assets:

```shell
php artisan vendor:publish --tag=nettoweb-laravel-cms
```

Use **admin@admin.com** and **password** to access the administrative section by opening the URL (replace *127.0.0.1* with your actual project location):
> http://127.0.0.1/admin

## Licensing

This project is licensed under MIT license.
