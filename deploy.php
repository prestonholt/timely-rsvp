<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'timelyrsvp.com');

// Project repository
set('repository', 'git@github.com:prestonholt/TimelyRSVP.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);


// Hosts

host('deployer@34.203.119.129')
    ->set('deploy_path', '/srv/{{application}}');    
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// Reload supervisor

task('supervisor', function () {
    run("cd {{release_path}} && supervisorctl reload");
});

// NPM run prod

task('npm', function () {
    run("cd {{release_path}} && npm ci && npm run prod");
});

before('deploy:symlink', 'supervisor');
before('deploy:symlink', 'npm');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

