# L8 Introduction



git clone `https://github.com/smart48/l8.git`

## Generate Auth Key and .env

```
php artisan key:generate  
```

Then add `.env` to `.gitignore`.

## Installation 

 `composer install` and `npm install` and `php artisan migrate`

## Local Host

Local docker will run on `localhost:8001` so you can keep Laravel Valet run sites running on .test.

indivifual container local ips can be checked using: `docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' container_name_or_id`:

```
docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' l8_app_1
172.18.0.2
docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' l8_web_1
172.18.0.3
```

## Local Docker Compose

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
Sdocker-compose down 
Stopping l8_web_1 ... done
Stopping l8_app_1 ... done
Stopping mysql    ... done
Removing l8_web_1 ... done
Removing l8_app_1 ... done
Removing mysql    ... done
Removing network l8_app-network
```


### List Containers

```
docker container ls 
CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS              PORTS                  NAMES
e4f55fa17a3b        nginx:latest        "nginx -g 'daemon of…"   3 seconds ago       Up 2 seconds        0.0.0.0:8001->80/tcp   l8_web_1
a78b3cdf6ce1        l8_app              "docker-entrypoint.s…"   3 seconds ago       Up 3 seconds        9000/tcp               l8_app_1
ba590f13c57d        mariadb:latest      "docker-entrypoint.s…"   4 seconds ago       Up 3 seconds        3306/tcp               mysql
```

