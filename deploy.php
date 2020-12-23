<?php

namespace Deployer;

require 'recipe/laravel.php';
require 'vendor/deployer/recipes/recipe/cachetool.php';

// Configuration
set('repository', 'git@github.com:smart48/smt-demo.git');
set('default_stage', 'production');
set('git_tty', true); // [Optional] Allocate tty for git on first deployment
set('ssh_type', 'native');
set('cachetool', '/var/run/php/php7.4-fpm.sock');
set('php_fpm_command', "kill -USR2 "$(ps -ef | grep '[p]hp-fpm: master' | awk '{print $2}')""); // for docker based setups with laradock or similar
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
  '.transip_private_key',
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
task('db:migrate', function () {
  run('cd {{release_path}} && php artisan migrate');
});
task('horizon:terminate', function () {
  run('cd {{release_path}} && php artisan horizon:terminate');
});

task('queue:restart', function () {
  run('cd {{release_path}} && php artisan queue:restart');
});

task('reload:php-fpm', function () {
  // run('sudo /etc/init.d/php7.4-fpm restart'); // Using SysV Init scripts
  run('{{php_fpm_command}}');
});


// Hosts
// local minikube host
// dep deploy 192.168.64.21
// `minikube ip` to get ip
// dep deploy production
// dep deploy staging


host('192.168.64.21')
  ->set('deploy_path', '/path/to/deployer')
  // kubectl exec -it workspace-566b747498-c6xs5 -- php 
  ->set('bin/php', 'cd /path/to/laradock/ && kubectl exec -t workspace -- php')
  ->set('bin/composer', 'cd /path/to/laradock/ && kubectl exec -t workspace -- composer -d={{release_path}}');

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

// Clear OPCache
after('queue:restart', 'cachetool:clear:opcache');
after('cachetool:clear:opcache', 'horizon:terminate');

after('deploy', 'reload:php-fpm');
after('rollback', 'reload:php-fpm');
