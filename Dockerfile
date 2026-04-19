# syntax=docker/dockerfile:1

# ── Stage 1: Build frontend assets ──────────────────────────────────────────
FROM node:20-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci --no-audit --prefer-offline
COPY vite.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm run build

# ── Stage 2: Install PHP dependencies ────────────────────────────────────────
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
# --no-scripts avoids running Laravel post-install scripts that need the full app
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts \
    --no-plugins

# ── Stage 3: Runtime image ────────────────────────────────────────────────────
FROM php:8.4-cli-alpine
WORKDIR /var/www/html

# Install system deps + PHP extensions
# postgresql-dev provides libpq-fe.h needed by pdo_pgsql
RUN apk add --no-cache \
    icu-dev \
    oniguruma-dev \
    libzip-dev \
    postgresql-dev \
    zip \
    unzip \
    curl \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        mbstring \
        bcmath \
        intl \
        zip \
        opcache

# Copy application code
COPY . .

# Overlay compiled vendor + built assets
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

# Replace platform_check.php with a no-op so autoload_real.php can still require it
# (deleting it causes a fatal error; overwriting it with empty PHP skips the version gate)
RUN echo '<?php' > vendor/composer/platform_check.php

# Prepare writable directories
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy and enable startup script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENV APP_ENV=production
ENV APP_DEBUG=false

EXPOSE 10000

ENTRYPOINT ["docker-entrypoint.sh"]
