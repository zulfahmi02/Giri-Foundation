#!/usr/bin/env bash

set -Eeuo pipefail

APP_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
WEB_ROOT="${CPANEL_WEB_ROOT:-$HOME/public_html}"
PHP_BIN="${CPANEL_PHP_BIN:-php}"
COMPOSER_BIN="${CPANEL_COMPOSER_BIN:-composer}"
RUN_MIGRATIONS="${CPANEL_RUN_MIGRATIONS:-1}"
BUILD_FRONTEND="${CPANEL_BUILD_FRONTEND:-0}"
RESTART_QUEUES="${CPANEL_RESTART_QUEUES:-1}"

log() {
    printf '[cpanel-deploy] %s\n' "$1"
}

run() {
    log "$*"
    "$@"
}

run_composer_install() {
    local composer_cmd="$1"
    shift || true

    local -a composer_install_command=(
        "$composer_cmd" install
        --no-dev
        --no-interaction
        --prefer-dist
        --optimize-autoloader
    )

    if run "${composer_install_command[@]}"; then
        return
    fi

    log "Composer install failed. Retrying without scripts for proc_open-restricted hosting."
    run "${composer_install_command[@]}" --no-scripts

    if [ ! -f "$APP_ROOT/bootstrap/cache/packages.php" ] || [ ! -f "$APP_ROOT/bootstrap/cache/services.php" ]; then
        log "Missing bootstrap/cache package manifest after --no-scripts install. Copy packages.php and services.php from a working release before switching the live app root."
        exit 1
    fi
}

resolve_composer_bin() {
    if command -v "$COMPOSER_BIN" >/dev/null 2>&1; then
        printf '%s\n' "$COMPOSER_BIN"

        return
    fi

    if [ -x "$HOME/bin/composer" ]; then
        printf '%s\n' "$HOME/bin/composer"

        return
    fi

    if [ -x /usr/local/bin/composer ]; then
        printf '%s\n' "/usr/local/bin/composer"

        return
    fi

    if [ -x /opt/cpanel/composer/bin/composer ]; then
        printf '%s\n' "/opt/cpanel/composer/bin/composer"

        return
    fi

    log "Composer binary not found. Set CPANEL_COMPOSER_BIN before deploying."
    exit 1
}

resolve_app_url() {
    "$PHP_BIN" -r "require '$APP_ROOT/vendor/autoload.php'; \$app = require '$APP_ROOT/bootstrap/app.php'; \$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class); \$kernel->bootstrap(); echo rtrim((string) config('app.url'), '/');"
}

sync_public_assets() {
    log "Syncing public assets into $WEB_ROOT"

    mkdir -p "$WEB_ROOT"

    if command -v rsync >/dev/null 2>&1; then
        run rsync -a \
            --exclude='index.php' \
            --exclude='robots.txt' \
            --exclude='storage' \
            --exclude='.well-known' \
            "$APP_ROOT/public/" "$WEB_ROOT/"

        return
    fi

    log "rsync not available, falling back to cp -R"
    find "$APP_ROOT/public" -mindepth 1 -maxdepth 1 \
        ! -name 'index.php' \
        ! -name 'robots.txt' \
        ! -name 'storage' \
        -exec /bin/cp -R {} "$WEB_ROOT/" \;
}

write_public_index() {
    local escaped_app_root

    escaped_app_root=$(printf '%s' "$APP_ROOT" | sed "s/'/'\\\\''/g")

    cat > "$WEB_ROOT/index.php" <<PHP
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

\$appRoot = '${escaped_app_root}';

if (file_exists(\$maintenance = \$appRoot.'/storage/framework/maintenance.php')) {
    require \$maintenance;
}

require \$appRoot.'/vendor/autoload.php';

/** @var Application \$app */
\$app = require_once \$appRoot.'/bootstrap/app.php';

\$app->handleRequest(Request::capture());
PHP
}

write_public_robots_txt() {
    local app_url

    app_url="$(resolve_app_url || true)"

    cat > "$WEB_ROOT/robots.txt" <<TXT
User-agent: *
Disallow: /admin
TXT

    if [ -n "$app_url" ]; then
        printf 'Sitemap: %s/sitemap.xml\n' "$app_url" >> "$WEB_ROOT/robots.txt"
    else
        log "APP_URL is empty; skipping Sitemap line in robots.txt"
    fi
}

link_public_storage() {
    rm -rf "$WEB_ROOT/storage"
    ln -sfn "$APP_ROOT/storage/app/public" "$WEB_ROOT/storage"
}

main() {
    local composer_cmd

    composer_cmd="$(resolve_composer_bin)"

    cd "$APP_ROOT"

    if [ ! -f "$APP_ROOT/.env" ]; then
        log "Missing .env file in $APP_ROOT. Create it on the server before deploying."
        exit 1
    fi

    mkdir -p "$APP_ROOT/storage" "$APP_ROOT/bootstrap/cache"
    chmod -R ug+rwx "$APP_ROOT/storage" "$APP_ROOT/bootstrap/cache" || true

    run_composer_install "$composer_cmd"

    if [ "$BUILD_FRONTEND" = "1" ]; then
        if command -v npm >/dev/null 2>&1; then
            run npm ci --no-audit --no-fund
            run npm run build
        else
            log "npm not available; skipping frontend build"
        fi
    fi

    run "$PHP_BIN" artisan optimize:clear

    if [ "$RUN_MIGRATIONS" = "1" ]; then
        run "$PHP_BIN" artisan migrate --force --no-interaction
    fi

    sync_public_assets
    write_public_index
    write_public_robots_txt
    link_public_storage

    run "$PHP_BIN" artisan optimize

    if [ "$RESTART_QUEUES" = "1" ]; then
        "$PHP_BIN" artisan queue:restart || true
    fi

    log "Deployment finished successfully."
}

main "$@"
