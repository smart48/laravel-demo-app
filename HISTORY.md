# L8 Introduction

## History

With the following command I created the Laravel application in directory l8. I then added the .docker, deploy and infratructure directories as well as docker-compose.yml.

```
laravel new l8
```

To including authentication right away and skip next step you can also run

```
laravel new blog --auth
```

## Generate Auth Key and .env

```
php artisan key:generate  
```

Then add `.env` to `.gitignore`.

## Models

See Models README for that. We added basic modules for a blog setup. These are stumps for now.


## Authentication

Laravel Authentication can be done with Laravel UI

[Laraval documentation](https://laravel.com/docs/7.x/authentication)

```
composer require laravel/ui
``

and

```
php artisan ui vue --auth
```

_This command should be used on fresh applications and will install a layout view, registration and login views, as well as routes for all authentication end-points. A HomeController will also be generated to handle post-login requests to your application's dashboard._

## IDE Helper

IDE helper to work better without needless error warnings in Visual Studio Code.

```
composer require --dev barryvdh/laravel-ide-helper
```

```
php artisan ide-helper:generate
```

## Laradock

First we need to adjust the .env file based on the exaxmple env file

```
cp env-example .env
```