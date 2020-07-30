# L8 Introduction

To get started with this demo application smt you can clone the application using:

```
git clone `https://github.com/smart48/smt-demo.git`
```


## Installation 

### Generate Auth Key and .env

```
php artisan key:generate  
```

### Packages

 `composer install` to install all PHP packages and `npm install` for the JS and Vue parts. 
  
 ## Migrations
 
 Then migrations using `php artisan migrate --database="nameOfConnection"`. We use `--database` as we work with two databases locally and in Kubernetes. So we have

 - `php artisan migrate --database="mysql"` for base database
 - `php artisan migrate --path=/database/migrations/published --database="mysql_published` for the secondary.

As of this writing the secondary database is still empty

**NB** Quick rollback `php artisan migrate:reset` 

## Laradock

Laradock based submodule has been added using `git submodule add https://github.com/smart48/smt-docker.git` and can be used from that folder.

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

Locally we work with `docker-compose.yml` inside the Laradock subdmodule folder. You can run this with `docker-compose.yml up -d`. See [Docker docs](https://docs.docker.com/compose/) . We do not use the default `docker-compose.yml` as we want to be able to update the submodule with ease when need be.

_Note: All the web server containers nginx, apache ..etc depends on php-fpm, which means if you run any of them, they will automatically launch the php-fpm container for you, so no need to explicitly specify it in the up command. If you have to do so, you may need to run them as follows: docker-compose up -d nginx php-fpm mysql._

https://laradock.io/getting-started/#Usage

**NB** We use port 9090 for http and 4433 for https for Nginx and 3307 and 3308 for MariaDB to avoid conflicts with Valet.

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

To set up Kubernetes with nodes and managed databases at Digital Ocean we use Terraform and Kubernetes. The package is added using `git submodule add https://github.com/smart48/smt-provision.git`

```
cd smt-provision
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

## Terraform Test
To check if the files will do their job you can run a test. But first add `smt-provision/terraform.tfvars` with

```
do_token = "key"
spaces_access_id = "access_key"
spaces_secret_key = "secre_key"
region = "ams3"
email = "jasper@imwz.io"
state_bucket = "tfstate"
helm_enabled = "true"
helm_nginx_ingress_classes = ["nginx-class-1"]
helm_nginx_ingress_replica_counts = ["2"]
kubernetes_name = "smt1"
name = "smt1"
kubernetes_context = "smt1"
kubernetes_version = "1.17.5-do.0"
kubernetes_node_size = "s-1vcpu-2gb"
# kubernetes_node_count = ""
kubernetes_autoscale = "true"
kubernetes_min_nodes = 1
kubernetes_max_nodes = 2
mysql_version ="8"
mysql_instances = ["smt1_mysql"]
mysql_node_sizes = ["db-s-1vcpu-2gb"]
mysql_node_counts = ["1"]
redis_version = "5"
redis_instances = ["smt1_redis"]
redis_node_sizes = ["db-s-1vcpu-2gb"]
redis_node_counts = ["1"]
```
and make sure to add your own Digital Ocean keys. Once done you can run:

```
terraform plan
```

to do a test run.