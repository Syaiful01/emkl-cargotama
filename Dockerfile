FROM php:8.2-apache

# 1. Gunakan skrip otomatis untuk instalasi extension PHP (Sangat Stabil)
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions intl gd zip bcmath exif pcntl opcache pdo_mysql mbstring

# 2. Konfigurasi Apache
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 3. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Setup Project
WORKDIR /var/www/html
COPY . .

# 5. Install Dependencies PHP & JS
RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get update && apt-get install -y nodejs && \
    npm install && \
    npm run build

# 6. Izin Folder & SQLite
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache && \
    touch /var/www/html/database/database.sqlite && \
    chown www-data:www-data /var/www/html/database/database.sqlite && \
    chmod 664 /var/www/html/database/database.sqlite

# 7. Jalankan Aplikasi
CMD php artisan migrate --force && apache2-foreground
