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

## Laradock

The Laradock `env.l8.demo.example` has been set up to work with:

1. Workspace,
2. PHP FPM, 
3. PHP Worker, 
4. Nginx,
5. Laravel Horizon,
6. Redis,
7. MariaDB, 
8. MariaDB v2, 
9. MailDev, 
10. BeanstalkD. 

Rename it to `.env` to work with it inside your project.

Locally we work with `docker-compose-l8.yml` inside the Laradock subdmodule folder. You can run this with `docker-compose -f docker-compose-l8.yml up -d`. See [Docker docs](https://docs.docker.com/compose/) . We do not use the default `docker-compose.yml` as we want to be able to update the submodule with ease when need be.

_Note: All the web server containers nginx, apache ..etc depends on php-fpm, which means if you run any of them, they will automatically launch the php-fpm container for you, so no need to explicitly specify it in the up command. If you have to do so, you may need to run them as follows: docker-compose up -d nginx php-fpm mysql._

https://laradock.io/getting-started/#Usage

**NB** Do make sure you ran `valet stop` if it interferes with you using `*.test` locally.
**NBB** .docker is still there as directory until we have finished the deploy directory loading from the proper DockerFile.

```
docker-compose up -d
...
...
```


And to shut them down

```
docker-compose down 
...
```


### List Containers

```
docker container ls 
...
```


## Infrastructure

To set up Kubernetes with nodes and managed databases at Digital Ocean we use Terraform and Kubernetes. You can find all these in the directory `infrastructure`. To start Terraform there use

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
