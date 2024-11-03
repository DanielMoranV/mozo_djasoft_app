# Usar una imagen base de PHP con Composer y extensiones necesarias
FROM php:8.2-fpm

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Establecer el directorio de trabajo
WORKDIR /var/www

# Copiar el contenido del proyecto a la imagen
COPY . .

# Instalar las dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Copiar los permisos de storage y bootstrap
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Establecer permisos de ejecución
RUN chmod -R 755 /var/www/storage

# Exponer el puerto 9000 y lanzar PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]
