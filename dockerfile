# Usa la imagen de PHP con Apache
FROM php:8.2-apache

# Sobrescribe la configuración predeterminada de Apache
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Instala las dependencias del sistema necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    libicu-dev \
    ca-certificates \
    lsb-release \
    gnupg \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql intl zip

# Instala Node.js y npm
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Habilita mod_rewrite en Apache
RUN a2enmod rewrite

# Copia los archivos de la aplicación al contenedor
COPY . /var/www/html

# Define el directorio de trabajo
WORKDIR /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --optimize-autoloader

# Ajusta los permisos para Apache
RUN chown -R www-data:www-data /var/www/html
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expone el puerto 80
EXPOSE 80