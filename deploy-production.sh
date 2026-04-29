#!/usr/bin/env bash

set -Eeuo pipefail

APP_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REMOTE_COMPOSER_DEFAULT='$HOME/bin/composer'

DEPLOY_HOST="${DEPLOY_HOST:-girifoundation.or.id}"
DEPLOY_PORT="${DEPLOY_PORT:-2223}"
DEPLOY_USER="${DEPLOY_USER:-girh8416}"
DEPLOY_PATH="${DEPLOY_PATH:-/home/girh8416/repositories/giri-foundation}"
DEPLOY_BRANCH="${DEPLOY_BRANCH:-main}"
DEPLOY_PHP_BIN="${DEPLOY_PHP_BIN:-php}"
DEPLOY_COMPOSER_BIN="${DEPLOY_COMPOSER_BIN:-$REMOTE_COMPOSER_DEFAULT}"
DEPLOY_RUN_MIGRATIONS="${DEPLOY_RUN_MIGRATIONS:-1}"
DEPLOY_RESTART_QUEUES="${DEPLOY_RESTART_QUEUES:-1}"

log() {
    printf '[deploy-production] %s\n' "$1"
}

sync_project_files() {
    ssh -p "$DEPLOY_PORT" "$DEPLOY_USER@$DEPLOY_HOST" "mkdir -p '$DEPLOY_PATH'"

    if command -v rsync >/dev/null 2>&1 && ssh -p "$DEPLOY_PORT" "$DEPLOY_USER@$DEPLOY_HOST" 'command -v rsync >/dev/null 2>&1'; then
        rsync -az --delete \
            --exclude='.env' \
            --exclude='.git/' \
            --exclude='.github/' \
            --exclude='bootstrap/cache/*.php' \
            --exclude='node_modules/' \
            --exclude='storage/' \
            --exclude='vendor/' \
            -e "ssh -p ${DEPLOY_PORT}" \
            "$APP_ROOT/" "${DEPLOY_USER}@${DEPLOY_HOST}:${DEPLOY_PATH}/"

        return
    fi

    if ! command -v tar >/dev/null 2>&1; then
        log 'tar is required on your local machine when rsync is unavailable on the server.'
        exit 1
    fi

    log 'rsync is unavailable on the server. Falling back to tar-over-SSH sync.'

    tar -C "$APP_ROOT" \
        --exclude='.env' \
        --exclude='.git' \
        --exclude='.github' \
        --exclude='bootstrap/cache/*.php' \
        --exclude='node_modules' \
        --exclude='storage' \
        --exclude='vendor' \
        -cf - . | ssh -p "$DEPLOY_PORT" "$DEPLOY_USER@$DEPLOY_HOST" \
            DEPLOY_PATH="$DEPLOY_PATH" \
            'bash -se' <<'BASH'
set -Eeuo pipefail

cd "$DEPLOY_PATH"

find . -mindepth 1 -maxdepth 1 \
    ! -name '.env' \
    ! -name '.git' \
    ! -name 'storage' \
    ! -name 'vendor' \
    -exec rm -rf {} +

tar -xf -
BASH
}

log "Syncing local project to ${DEPLOY_USER}@${DEPLOY_HOST}:${DEPLOY_PATH}"
sync_project_files

log "Running deployment commands on ${DEPLOY_HOST}"

ssh -p "$DEPLOY_PORT" "$DEPLOY_USER@$DEPLOY_HOST" \
    DEPLOY_PATH="$DEPLOY_PATH" \
    DEPLOY_BRANCH="$DEPLOY_BRANCH" \
    DEPLOY_PHP_BIN="$DEPLOY_PHP_BIN" \
    DEPLOY_COMPOSER_BIN="$DEPLOY_COMPOSER_BIN" \
    DEPLOY_RUN_MIGRATIONS="$DEPLOY_RUN_MIGRATIONS" \
    DEPLOY_RESTART_QUEUES="$DEPLOY_RESTART_QUEUES" \
    'bash -se' <<'BASH'
set -Eeuo pipefail

cd "$DEPLOY_PATH"

"$DEPLOY_COMPOSER_BIN" install --no-dev --prefer-dist --optimize-autoloader --no-scripts
"$DEPLOY_PHP_BIN" artisan package:discover --ansi
"$DEPLOY_PHP_BIN" artisan optimize:clear

if [ "$DEPLOY_RUN_MIGRATIONS" = "1" ]; then
    "$DEPLOY_PHP_BIN" artisan migrate --force --no-interaction
fi

"$DEPLOY_PHP_BIN" artisan optimize

if [ "$DEPLOY_RESTART_QUEUES" = "1" ]; then
    "$DEPLOY_PHP_BIN" artisan queue:restart || true
fi
BASH

log "Deployment finished successfully."
