FROM richarvey/nginx-php-fpm:1.23

# Configuración básica para Render
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Copiar todo el código
COPY . /var/www/html

# Configurar permisos para public/
RUN mkdir -p /var/www/html/public/storage && \
    chown -R www-data:www-data /var/www/html/public && \
    chmod -R 755 /var/www/html/public/storage

# Exponer el puerto
EXPOSE 80

# Comando de inicio (usa nginx-php-fpm)
CMD php-fpm -D && nginx -g "daemon off;"
