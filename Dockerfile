# Set the base image for subsequent instructions
FROM php:7.3.13

COPY api.gandagang.com.conf /usr/local/etc/nginx/sites-available/
COPY src/ /var/www/gandagang.com/
WORKDIR /var/www/gandagang.com/

# Update packages
RUN apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends apt-utils \
    wget ca-certificates apt-transport-https gnupg2
ENV DEBIAN_FRONTEND teletype

# Install PHP and composer dependencies
RUN apt-get install -qq git curl libmcrypt-dev libjpeg-dev libzip-dev libpng-dev libfreetype6-dev libbz2-dev && \
    pecl install mcrypt-1.0.3 && \
    docker-php-ext-enable mcrypt

# Clear out the local repository of retrieved package files
RUN apt-get clean

# Install needed extensions
# Here you can install any other extension that you need during the test and deployment process
RUN docker-php-ext-install pdo_mysql gd zip bz2 opcache

# docker timeone
ENV TZ=Asia/Jakarta
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime
RUN echo $TZ > /etc/timezone

# Install Composer
RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Laravel Envoy
RUN composer global require "laravel/lumen-installer"

EXPOSE 80