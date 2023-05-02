# In terminal, run: chmod +x build.sh
#!/bin/bash

# This is a custom build script for a Laravel project on Digital Ocean App Platform.
# It installs the required PHP exif extension.

# Update package list
apt-get update

# Install PHP exif extension
apt-get install -y php8.2-exif

# Create a symlink for the storage folder
php artisan storage:link