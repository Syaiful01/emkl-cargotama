FROM php:8.2-apache

# 1. Set environment variable
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV CACHE_STORE=file
ENV SESSION_DRIVER=file
ENV LOG_CHANNEL=stderr

# 2. Install dependencies sistem
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    curl \
    libicu-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Install extension PHP
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions intl gd zip bcmath exif pcntl opcache pdo_mysql mbstring

# 4. Konfigurasi Apache
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Setup Project
WORKDIR /var/www/html
COPY . .

# 7. Siapkan Folder & File Database (PENTING: Di tahap build)
RUN mkdir -p database storage bootstrap/cache && \
    touch database/database.sqlite && \
    chmod -R 777 storage bootstrap/cache database && \
    chown -R www-data:www-data .

# 8. Install Dependencies & Optimize (Biar boot cepat)
RUN composer install --no-interaction --optimize-autoloader --no-dev --no-scripts
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    npm install && \
    npm run build

# 9. Pre-generate cache
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# 10. Jalankan Aplikasi (Hanya migrasi yang dijalankan saat boot)
CMD ["sh", "-c", "php artisan migrate --force && apache2-foreground"]
