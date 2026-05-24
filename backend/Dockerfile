FROM php:8.4-cli-alpine
# build v4
WORKDIR /var/www/html

# System deps
RUN apk add --no-cache \
    curl zip unzip git nodejs npm \
    libpng-dev libzip-dev oniguruma-dev \
    libxml2-dev freetype-dev libjpeg-turbo-dev libsodium-dev \
    libwebp-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install \
        pdo pdo_mysql mbstring zip gd xml bcmath pcntl exif sodium

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy files
COPY . .

# Create minimal .env for build-time artisan commands
RUN echo "APP_NAME=GamingGears" > .env \
    && echo "APP_ENV=production" >> .env \
    && echo "APP_KEY=base64:$(head -c 32 /dev/urandom | base64)" >> .env \
    && echo "APP_DEBUG=false" >> .env \
    && echo "DB_CONNECTION=mysql" >> .env

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader

# Clear any build-time cache artifacts
RUN rm -f bootstrap/cache/routes-v7.php \
    && rm -f bootstrap/cache/config.php \
    && rm -f bootstrap/cache/packages.php \
    && rm -f bootstrap/cache/services.php

# Build client + SSR bundle
RUN npm install \
    && php artisan ziggy:generate \
    && npm run build:ssr

# Remove build-time .env
RUN rm .env

# Permissions
RUN chown -R nobody:nobody storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy start script
COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8080
CMD ["/start.sh"]
