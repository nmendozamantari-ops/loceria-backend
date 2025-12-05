FROM heroku/heroku:20-build as build

COPY . /app/
WORKDIR /app

RUN mkdir -p /tmp/label
RUN echo -e "heroku\n20\nbuild\nheroku:latest\nheroku:latest" > /tmp/label/VERSION_LABELS
RUN mkdir -p /tmp/cache
RUN curl --silent --location --retry 3 --max-time 60 https://getcomposer.org/download/latest-stable/composer.phar --output /tmp/cache/composer.phar
RUN chmod +x /tmp/cache/composer.phar
RUN /tmp/cache/composer.phar install --no-dev --no-interaction --no-scripts --prefer-dist --optimize-autoloader --no-suggest --no-progress

FROM heroku/heroku:20

COPY --from=build /app /app
WORKDIR /app

COPY .platform /app/.platform 2>/dev/null || true

RUN /bin/bash -c "mkdir -p /etc/apache2/sites-available /etc/apache2/sites-enabled /var/log/apache2 /var/lock/apache2 /var/run/apache2 /var/www/html /var/tmp"
RUN /bin/bash -c "ln -sf /dev/stdout /var/log/apache2/access.log"
RUN /bin/bash -c "ln -sf /dev/stderr /var/log/apache2/error.log"
RUN /bin/bash -c "ln -sf /dev/stderr /var/log/apache2/other.log"
RUN /bin/bash -c "chown www-data:www-data /var/log/apache2 /var/lock/apache2 /var/run/apache2 /var/www/html /var/tmp"

COPY .platform/server.conf /etc/apache2/sites-available/000-default.conf

RUN mkdir -p /app/public
RUN chown -R www-data:www-data /app/public
RUN chown -R www-data:www-data /app/storage

EXPOSE 8080
CMD ["vendor/bin/heroku-php-apache2", "public/"]
