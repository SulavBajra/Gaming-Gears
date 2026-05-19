FROM php:8.4-cli-alpine
WORKDIR /var/www/html

# System deps
RUN apk add --no-cache \
    curl zip unzip git nodejs npm \
    libpng-dev libzip-dev oniguruma-dev \
    libxml2-dev freetype-dev libjpeg-turbo-dev libsodium-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo pdo_mysql mbstring zip gd xml bcmath pcntl exif sodium

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy files
COPY . .

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader

# Build client + SSR bundle
RUN npm install && npm run build:ssr

# Permissions
RUN chown -R nobody:nobody storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8080

# Start SSR node server in background, then PHP
CMD php artisan config:cache \
    && php artisan route:cache \
    && php artisan migrate --force \
    && php artisan inertia:start-ssr & \
    php artisan serve --host=0.0.0.0 --port=8080
