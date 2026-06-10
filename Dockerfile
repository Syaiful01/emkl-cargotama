FROM php:8.2-apache

# 1. Install dependencies sistem & tools pembangunan
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    g++ \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Install extension PHP yang dibutuhkan Laravel
RUN docker-php-ext-configure intl \
    && docker-php-ext-install \
    intl \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    opcache

# 3. Aktifkan modul Apache Rewrite (Penting untuk Laravel)
RUN a2enmod rewrite

# 4. Atur Document Root Apache ke folder /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Copy Composer dari image resmi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Salin kode aplikasi ke dalam container
WORKDIR /var/www/html
COPY . .

# 7. Install dependencies PHP (tanpa dev tools untuk performa)
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 8. Install Node.js & Build Assets (Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install \
    && npm run build \
    && rm -rf node_modules

# 9. Atur izin folder (Penting agar server bisa menulis data)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 10. Siapkan database SQLite kosong
RUN touch /var/www/html/database/database.sqlite \
    && chown www-data:www-data /var/www/html/database/database.sqlite \
    && chmod 664 /var/www/html/database/database.sqlite

# 11. Port default
EXPOSE 80

# 12. Jalankan migrasi dan start server
CMD php artisan migrate --force && apache2-foreground
