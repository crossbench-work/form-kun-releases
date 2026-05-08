# form-kun: PHP + Apache（mod_rewrite / .htaccess 対応）
FROM php:8.2-apache-bookworm

RUN a2enmod rewrite headers \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

COPY docker/php-apache/000-default.conf /etc/apache2/sites-available/000-default.conf

COPY docker/php-apache/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

WORKDIR /var/www/html

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
