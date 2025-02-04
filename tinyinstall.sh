#!/bin/sh

# Ensure the script is run as root
if [ "$(id -u)" -ne 0 ]; then
    echo "Please run this script as root or use sudo"
    exit 1
fi

# Detect installed PHP version
PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")

echo "Installing PHP extensions for PHP $PHP_VERSION on Alpine Linux..."

# Update package repository
apk update

# Install necessary PHP extensions
apk add --no-cache \
    php-fileinfo \
    php-iconv \
    php-zip \
    php-phar \
    php-mbstring \
    php-ctype \
    pho-session

# Restart PHP service (if applicable)

    rc-service apache2 restart
    echo "apache2 restarted."


echo "PHP extensions installed successfully!"
