FROM php:8.2-apache

COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    cron \
    vim \
    libzip-dev \
    libicu-dev \
    ca-certificates \
    lsb-release \
    gnupg \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql intl zip

RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

RUN a2enmod rewrite

COPY . /var/www/html

WORKDIR /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

RUN echo "* * * * * cd /var/www/html && /usr/local/bin/php /var/www/html/artisan schedule:run >> /dev/null 2>&1"
RUN chmod 0644 /etc/cron.d/laravel-schedule
RUN crontab /etc/cron.d/laravel-schedule

CMD cron && apache2-foreground

EXPOSE 80