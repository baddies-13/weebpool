FROM php:8.2-apache
COPY php/ /var/www/html/
COPY css/ /var/www/html/css/
COPY js/ /var/www/html/js/
COPY images/ /var/www/html/images/
RUN docker-php-ext-install mysqli
EXPOSE 80