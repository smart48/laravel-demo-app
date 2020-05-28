# L8 Introduction

To get started with this demo application L8 you can clone the application using:

```
git clone `https://github.com/smart48/l8.git`
```
## Generate Auth Key and .env

```
php artisan key:generate  
```

Then add `.env` to `.gitignore`.

## Installation 

 `composer install` to install all PHP packages and `npm install` for the JS and Vue parts. 
 
 ## Migrations
 
 Then migrations using `php artisan migrate --database="nameOfConnection"`. We use `--database` as we work with two databases locally and in Kubernetes. So we have

 - `php artisan migrate --database="mysql"` for base database
 - `php artisan migrate --path=/database/migrations/published --database="mysql_published` for the secondary.

As of this writing the secondary database is still empty

**NB** Quick rollback `php artisan migrate:reset` 

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

You can add all environmental variables to `.docker/.env`, but they won't load unless you run `eval $(egrep -v '^#' .docker/.env | xargs) docker-compose config` because somehow we have to have .env in same directory and there we already have the one for Laravel!

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


## Infrastructure

To set up Kubernetes with nodes and managed databases we use Terraform and Kubernetes. You can find all these in the directory `infrastructure`. To start Terraform there use

```
terraform init
```

You should see something like

```
Initializing the backend...

Initializing provider plugins...
- Checking for available provider plugins...
- Downloading plugin for provider "helm" (hashicorp/helm) 1.2.1...
- Downloading plugin for provider "digitalocean" (terraform-providers/digitalocean) 1.18.0...

The following providers do not have any version constraints in configuration,
so the latest version was installed.

To prevent automatic upgrades to new major versions that may contain breaking
changes, it is recommended to add version = "..." constraints to the
corresponding provider blocks in configuration, with the constraint strings
suggested below.

* provider.digitalocean: version = "~> 1.18"
* provider.helm: version = "~> 1.2"
* provider.null: version = "~> 2.1"

Terraform has been successfully initialized!

You may now begin working with Terraform. Try running "terraform plan" to see
any changes that are required for your infrastructure. All Terraform commands
should now work.
```
To check if the files will do their job you can run a 

```
terraform plan
```
