FROM php:8.2-apache

# 1. Set environment variable untuk Composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# 2. Install dependencies sistem (termasuk unzip untuk memperbaiki log tadi)
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
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Setup Project
WORKDIR /var/www/html
COPY . .

# 7. Install PHP Dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 8. Install Node.js & Build Assets
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get update && apt-get install -y nodejs && \
    npm install && \
    npm run build && \
    rm -rf node_modules

# 9. Izin Folder & SQLite
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache && \
    touch /var/www/html/database/database.sqlite && \
    chown www-data:www-data /var/www/html/database/database.sqlite && \
    chmod 664 /var/www/html/database/database.sqlite

# 10. Jalankan Aplikasi
CMD php artisan migrate --force && apache2-foreground
