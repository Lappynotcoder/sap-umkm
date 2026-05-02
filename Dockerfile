FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    oniguruma-dev \
    icu-dev \
    libzip-dev

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    zip \
    opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first (layer cache optimization)
COPY composer.json composer.lock ./

# Install PHP dependencies (no dev, optimized autoloader)
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts

# Copy package.json untuk npm
COPY package.json package-lock.json ./

# Install npm dependencies dan build Vite assets
RUN npm install && npm run build && rm -rf node_modules

# Copy seluruh source code
COPY . .

# Jalankan post-install scripts composer
RUN composer run-script post-autoload-dump 2>/dev/null || true

# Set permission storage dan cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# OPcache - hemat RAM di production
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=64'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=4000'; \
    echo 'opcache.revalidate_freq=60'; \
    echo 'opcache.fast_shutdown=1'; \
} > /usr/local/etc/php/conf.d/opcache.ini

# PHP-FPM tuning untuk RAM 1 GB
RUN { \
    echo '[www]'; \
    echo 'pm = dynamic'; \
    echo 'pm.max_children = 5'; \
    echo 'pm.start_servers = 2'; \
    echo 'pm.min_spare_servers = 1'; \
    echo 'pm.max_spare_servers = 3'; \
    echo 'pm.max_requests = 500'; \
} > /usr/local/etc/php-fpm.d/zz-custom.conf

EXPOSE 9000
CMD ["php-fpm"]
