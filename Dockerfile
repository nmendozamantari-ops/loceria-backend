FROM php:8.2-apache

# Instalar extensiones PHP necesarias (mysqli para MySQL, si las usas)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilitar mod_rewrite para .htaccess
RUN a2enmod rewrite

# Copiar el código del proyecto
COPY . /var/www/html/

# Configurar Apache para servir desde la carpeta public/
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Asegurar que index.php sea el index por defecto
RUN echo "DirectoryIndex index.php" >> /etc/apache2/sites-available/000-default.conf

# Configurar permisos para Apache
RUN chown -R www-data:www-data /var/www/html/public && \
    chmod -R 755 /var/www/html/public

# Exponer puerto 80
EXPOSE 80

# Comando de inicio de Apache
CMD ["apache2-foreground"]
