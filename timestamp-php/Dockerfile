FROM php:8.2-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Install zip extension
RUN apt-get update && apt-get install -y libzip-dev \
    && docker-php-ext-install zip
