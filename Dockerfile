FROM php:8.2-apache

# 1. Set environment variable agar Laravel tidak protes saat build
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV CACHE_STORE=array
ENV SESSION_DRIVER=array
ENV LOG_CHANNEL=stderr

# 2. Install dependencies sistem
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    curl \
    libicu-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Install extension PHP menggunakan installer otomatis
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions intl gd zip bcmath exif pcntl opcache pdo_mysql mbstring

# 4. Konfigurasi Apache
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Setup Project & Siapkan Folder
WORKDIR /var/www/html
COPY . .
RUN mkdir -p database storage bootstrap/cache && \
    touch database/database.sqlite && \
    chmod -R 775 storage bootstrap/cache database && \
    chown -R www-data:www-data .

# 7. Install PHP Dependencies (Mengabaikan script otomatis agar tidak error database)
RUN composer install --no-interaction --optimize-autoloader --no-dev --no-scripts

# 8. Install Node.js & Build Assets
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get update && apt-get install -y nodejs && \
    npm install && \
    npm run build && \
    rm -rf node_modules

# 9. Jalankan Aplikasi (Migrasi akan dilakukan saat container menyala)
CMD ["sh", "-c", "php artisan migrate --force && php artisan optimize && apache2-foreground"]
