# Stage 1: Composer (PHP Dependencies)
FROM composer:2.7 AS vendor

WORKDIR /app

# Copy composer files first to leverage Docker caching
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --ignore-platform-reqs

# Stage 2: Node (Frontend Assets)
FROM node:20-alpine AS frontend

WORKDIR /app

# Copy package files
COPY package.json package-lock.json ./

# Install npm dependencies
RUN npm ci

# Copy the rest of the application files needed for building assets
COPY vite.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/

# Build assets using Vite
RUN npm run build

# Stage 3: Production Runtime (PHP-Apache)
FROM php:8.3-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libonig-dev \
    libicu-dev \
    && rm -rf /var/lib/apt/lists/*

# Install common PHP extensions required by Laravel
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    xml \
    bcmath \
    gd \
    zip \
    intl \
    pcntl \
    exif \
    && docker-php-ext-enable opcache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Update Apache DocumentRoot to Laravel's public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files (excluding those in .dockerignore)
COPY . .

# Copy vendor directory from the composer stage
COPY --from=vendor /app/vendor/ ./vendor/

# Copy built frontend assets from the node stage
COPY --from=frontend /app/public/build/ ./public/build/

# Ensure storage and bootstrap/cache directories exist and have proper permissions
RUN mkdir -p storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

