<?php

use Illuminate\Support\Facades\File;

test('cpanel deployment files exist and reference the deploy script', function () {
    expect(base_path('.cpanel.yml'))->toBeFile()
        ->and(base_path('cpanel-deploy.sh'))->toBeFile();

    $deploymentConfig = File::get(base_path('.cpanel.yml'));
    $deployScript = File::get(base_path('cpanel-deploy.sh'));

    expect($deploymentConfig)->toContain('deployment:')
        ->and($deploymentConfig)->toContain('/bin/bash ./cpanel-deploy.sh')
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
        ->and($deployScript)->toContain('robots.txt');
});
