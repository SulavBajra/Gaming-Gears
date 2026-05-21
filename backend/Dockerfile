FROM php:8.4-cli-alpine
# build v3
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

# Build client + SSR bundle
RUN npm install && npm run build:ssr

# Remove build-time .env (real one comes from Render env vars at runtime)
RUN rm .env

# Permissions
RUN chown -R nobody:nobody storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8080

CMD php artisan config:clear \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan migrate --force \
    && php artisan db:seed --force \
    && php artisan inertia:start-ssr & \
    php artisan serve --host=0.0.0.0 --port=8080
