# L8 Introduction

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

See Models README for that.

## Hosts

Local docker will run on `localhost:8001` so you can keep Laravel Valet run sites running on .test.

indivifual container local ips can be checked using: `docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' container_name_or_id`:

```
docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' l8_app_1
172.18.0.2
docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' l8_web_1
172.18.0.3
```

## Docker

Locally we work with `docker-compose.yml` You can run this with `docker-compose up -d` as it is the default. See [Docker docs](https://docs.docker.com/compose/)

Do add all environmental variables to `.docker/.env`!

NB Do make sure you ran `valet stop` if it interferes with you using `*.test` locally.

```
docker-compose up -d
Creating network "l8_app-network" with driver "bridge"
Building app
Step 1/24 : FROM php:7.4-fpm-buster
7.4-fpm-buster: Pulling from library/php
afb6ec6fdc1c: Pull complete
3d895574014b: Pull complete
...
```

If all installed you will see
`
```
docker-compose up -d
Starting l8_app_1 ... done
Starting l8_web_1 ... done
..
```

And to shut them down

```
docker-compose down 
Stopping l8_app_1 ... done
Stopping l8_web_1 ... done
Removing l8_app_1 ... done
Removing l8_web_1 ... done
Removing network l8_app-network
```


### List Containers

```
docker container ls 
CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS              PORTS                  NAMES
c2ac2f152579        l8_app              "docker-entrypoint.s…"   2 seconds ago       Up 2 seconds        9000/tcp               l8_app_1
2d5903fd452d        nginx:mainline      "nginx -g 'daemon of…"   2 seconds ago       Up 1 second         0.0.0.0:8001->80/tcp   l8_web_1
```

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