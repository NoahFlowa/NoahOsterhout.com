# php:8.5-apache is tag-pinned to the 8.5 minor, not a digest.
FROM php:8.5-apache

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini \
    && a2enmod rewrite headers \
    && sed -i 's/Listen 80/Listen 3000/' /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:3000>/' /etc/apache2/sites-available/000-default.conf \
    && sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf \
    && sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/Options Indexes FollowSymLinks/Options +FollowSymLinks -Indexes -MultiViews/' /etc/apache2/apache2.conf

COPY docker/apache/site.conf /etc/apache2/conf-available/site.conf
COPY docker/php/zz-site.ini /usr/local/etc/php/conf.d/zz-site.ini
RUN a2enconf site

COPY . /var/www/html/

# The web root stays root-owned and read-only. www-data only needs to read it.
# Drop the proxy config and tests so they are not served.
RUN rm -rf /var/www/html/docker /var/www/html/tests \
    && chown -R root:root /var/www/html \
    && find /var/www/html -type d -exec chmod 555 {} + \
    && find /var/www/html -type f -exec chmod 444 {} +

EXPOSE 3000

CMD ["apache2-foreground"]
