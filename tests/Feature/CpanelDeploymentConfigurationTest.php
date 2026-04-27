<?php

use Illuminate\Support\Facades\File;

test('cpanel deployment files exist and reference the deploy script', function () {
    expect(base_path('.cpanel.yml'))->toBeFile()
        ->and(base_path('cpanel-deploy.sh'))->toBeFile();

    $deploymentConfig = File::get(base_path('.cpanel.yml'));
    $deployScript = File::get(base_path('cpanel-deploy.sh'));

    expect($deploymentConfig)->toContain('deployment:')
        ->and($deploymentConfig)->toContain('/bin/bash ./cpanel-deploy.sh')
        ->and($deployScript)->toContain('install --no-dev --no-interaction')
        ->and($deployScript)->toContain('artisan optimize:clear')
        ->and($deployScript)->toContain('artisan migrate --force --no-interaction')
        ->and($deployScript)->toContain('artisan optimize')
        ->and($deployScript)->toContain('artisan queue:restart')
        ->and($deployScript)->toContain('public_html')
        ->and($deployScript)->toContain('robots.txt');
});
