FROM php:8.0-apache

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Instalar dependencias necesarias para PHP y CodeIgniter
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-install pdo_mysql mysqli zip intl gd

# Configurar el módulo rewrite para Apache
RUN a2enmod rewrite

# Copiar la configuración de Apache personalizada
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiar el código de CodeIgniter
COPY . /var/www/html

# Establecer permisos adecuados
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto 80
EXPOSE 80

# Iniciar Apache
CMD ["apache2-foreground"]