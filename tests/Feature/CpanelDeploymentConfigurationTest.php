<?php

use Illuminate\Support\Facades\File;

test('cpanel deployment files exist and reference the deploy script', function () {
    expect(base_path('.cpanel.yml'))->toBeFile()
        ->and(base_path('.github/workflows/deploy-cpanel.yml'))->toBeFile()
        ->and(base_path('cpanel-deploy.sh'))->toBeFile()
        ->and(base_path('deploy-production.sh'))->toBeFile();

    $deploymentConfig = File::get(base_path('.cpanel.yml'));
    $workflowConfig = File::get(base_path('.github/workflows/deploy-cpanel.yml'));
    $deployScript = File::get(base_path('cpanel-deploy.sh'));
    $productionDeployScript = File::get(base_path('deploy-production.sh'));

    expect($deploymentConfig)->toContain('deployment:')
        ->and($deploymentConfig)->toContain('/bin/bash ./cpanel-deploy.sh')
        ->and($workflowConfig)->toContain('rsync -az --delete')
        ->and($workflowConfig)->toContain('Run deployment on server')
        ->and($workflowConfig)->toContain('/bin/bash ./cpanel-deploy.sh')
        ->and($workflowConfig)->toContain('export CPANEL_COMPOSER_BIN=\$HOME/bin/composer')
        ->and($deployScript)->toContain('install')
        ->and($deployScript)->toContain('--no-dev')
        ->and($deployScript)->toContain('--no-interaction')
        ->and($deployScript)->toContain('--no-scripts')
        ->and($deployScript)->toContain('proc_open-restricted hosting')
        ->and($deployScript)->toContain('artisan package:discover --ansi')
        ->and($deployScript)->toContain('artisan optimize:clear')
        ->and($deployScript)->toContain('artisan migrate --force --no-interaction')
        ->and($deployScript)->toContain('artisan optimize')
        ->and($deployScript)->toContain('artisan queue:restart')
        ->and($deployScript)->toContain('public_html')
        ->and($deployScript)->toContain('robots.txt')
        ->and($productionDeployScript)->toContain('rsync -az --delete')
        ->and($productionDeployScript)->toContain('tar-over-SSH sync')
        ->and($productionDeployScript)->toContain("mkdir -p '\$DEPLOY_PATH'")
        ->and($productionDeployScript)->toContain('find . -mindepth 1 -maxdepth 1')
        ->and($productionDeployScript)->toContain('artisan package:discover --ansi')
        ->and($productionDeployScript)->toContain('artisan optimize:clear')
        ->and($productionDeployScript)->toContain('artisan migrate --force --no-interaction')
        ->and($productionDeployScript)->toContain('artisan optimize')
        ->and($productionDeployScript)->toContain('queue:restart');
});
