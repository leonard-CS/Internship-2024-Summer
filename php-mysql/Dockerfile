# Dockerfile

# Use an official PHP runtime as a parent image
FROM php:8.1-apache

# Install additional PHP extensions if needed
RUN docker-php-ext-install mysqli

# Copy custom PHP configuration (if any)
# COPY php.ini /usr/local/etc/php/

# Copy the application code to the container
COPY src/ /var/www/html/
