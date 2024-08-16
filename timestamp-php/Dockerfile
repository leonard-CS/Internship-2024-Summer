# Use an official PHP image as the base
FROM php:8.2-apache

# Copy the source directory contents into the container
COPY src/ /var/www/html

RUN echo "ServerName logalhost" >> /etc/apache2/apache2.conf

# Expose port 80
EXPOSE 80
