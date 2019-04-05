# Laravel-settings

## A solution to manage your applications settings using the database

 * Simple key-value storage
 * Support multi-level array (dot delimited keys) structure.


## Installation

1. Install package

    ```bash
    composer require omaraligit/laravel-settings
    ```

1. Edit config/app.php (Skip this step if you are using laravel 5.5+)

    service provider:

    ```php
    OmarAliGit\Settings\LaravelSettingsServiceProvider::class,
    ```


1. Create settings table

    ```bash
    php artisan vendor:publish
    php artisan migrate
    ```

## Usage

```php
// ...

```