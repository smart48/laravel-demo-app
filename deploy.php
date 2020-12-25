<?php

namespace Deployer;

require 'recipe/laravel.php';
// require 'vendor/deployer/recipes/recipe/cachetool.php';

// Configuration
set('repository', 'git@github.com:smart48/smt-demo.git');
set('default_stage', 'production');
set('git_tty', true); // [Optional] Allocate tty for git on first deployment
set('ssh_type', 'native');
// set('cachetool', '/var/run/php/php7.4-fpm.sock');
// set('php_fpm_command', "kill -USR2 "$(ps -ef | grep '[p]hp-fpm: master' | awk '{print $2}')""); // for docker based setups with laradock or similar
set('keep_releases', 10);

// Make sure uploads & published , tls, logs aren't overwritten by deploying
set('shared_dirs', [
  'public/uploads',
  'public/published',
  'storage/logs',
  'storage/tls',
  'storage/app/public',
]);
set('shared_files', [
  '.env',
]);
set('writable_dirs', [
  'public/uploads',
  'public/published',
  'storage/framework/cache/data',
  'storage/logs',
  'storage/tls',
  'storage/app/public',
]);

// SMART CUSTOM DEPLOY COMMANDS
// new `run('{{bin/php}} {{release_path}}/artisan migrate --force');`
// old `run('cd {{release_path}} && php artisan migrate');`
task('db:migrate', function () {
  run('{{bin/php}} {{release_path}}/artisan migrate --force');
});
task('horizon:terminate', function () {
  run('{{bin/php}} {{release_path}}/artisan horizon:terminate');
});

task('queue:restart', function () {
  run('{{bin/php}} {{release_path}}/artisan queue:restart');
});

// not on workspace yet
// task('reload:php-fpm', function () {
//   run('sudo /etc/init.d/php7.4-fpm restart'); // Using SysV Init scripts
//   // run('{{php_fpm_command}}');
// });


// Hosts
// local minikube host
// dep deploy $(minikube ip)
// `minikube ip` to get ip
// dep deploy production
// dep deploy staging


host('192.168.64.25')
  ->user('docker')
  ->identityFile('~/.minikube/machines/minikube/id_rsa')
  ->set('deploy_path', '/tmp/hostpath-provisioner/smt-local/code')
  // docker exec -it $(docker ps | grep smt-workspace | awk '{print $1}') /bin/bash
  ->set('bin/php', "docker exec -t $(docker ps | grep smt-workspace | awk '{print $1}')  bash -c 'cd release | php'")
  ->set('bin/composer', "docker exec -t $(docker ps | grep smt-workspace | awk '{print $1}') bash -c 'cd release && composer install'");

host('staging')
  ->hostname('staging.domain.com')
  ->user('forge')
  ->forwardAgent()
  ->stage('staging')
  ->set('deploy_path', '/home/forge/staging.domain.com');

host('production')
  ->hostname('domain.com')
  ->user('forge')
  ->forwardAgent()
  ->stage('production')
  ->set('deploy_path', '/home/forge/domain.com');


// Run database migrations
after('deploy:symlink', 'db:migrate');

// Restart job
after('db:migrate', 'queue:restart');

// New version is needed
// Clear OPCache
// after('queue:restart', 'cachetool:clear:opcache');
// after('cachetool:clear:opcache', 'horizon:terminate');

// not on Minikube as of yet
// after('deploy', 'reload:php-fpm');
// after('rollback', 'reload:php-fpm');
