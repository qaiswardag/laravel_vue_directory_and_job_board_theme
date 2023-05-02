#!/bin/bash

# Update package list
apt-get update

# Install PHP exif extension
apt-get install -y php8.2-exif

# Install composer dependencies
composer install