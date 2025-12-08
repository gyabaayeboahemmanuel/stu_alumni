#!/bin/bash

# STU Alumni System Initial Setup Script
# Run this on a fresh server installation

set -e

echo "ðŸŽ¯ STU Alumni System Initial Setup"
echo "================================="

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    echo "Please run as root or with sudo"
    exit 1
fi

# Update system
echo "1. Updating system packages..."
apt update && apt upgrade -y

# Install required packages
echo "2. Installing required packages..."
apt install -y \
    nginx \
    mysql-server \
    php8.1-fpm \
    php8.1-mysql \
    php8.1-mbstring \
    php8.1-xml \
    php8.1-bcmath \
    php8.1-curl \
    php8.1-zip \
    php8.1-gd \
    php8.1-intl \
    git \
    curl \
    unzip \
    supervisor

# Install Composer
echo "3. Installing Composer..."
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js (for frontend assets)
curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
apt install -y nodejs

# Create application directory
echo "4. Setting up application directory..."
mkdir -p /var/www/stu-alumni
chown -R www-data:www-data /var/www/stu-alumni

# Create backup directory
mkdir -p /var/backups/stu-alumni
chown -R www-data:www-data /var/backups/stu-alumni

# Configure MySQL
echo "5. Configuring MySQL..."
mysql -e "CREATE DATABASE IF NOT EXISTS stu_alumni CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -e "CREATE USER IF NOT EXISTS 'stu_alumni_user'@'localhost' IDENTIFIED BY 'strong_password_here';"
mysql -e "GRANT ALL PRIVILEGES ON stu_alumni.* TO 'stu_alumni_user'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

# Configure PHP
echo "6. Configuring PHP..."
sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 10M/' /etc/php/8.1/fpm/php.ini
sed -i 's/post_max_size = 8M/post_max_size = 10M/' /etc/php/8.1/fpm/php.ini
sed -i 's/memory_limit = 128M/memory_limit = 256M/' /etc/php/8.1/fpm/php.ini

# Configure Nginx
echo "7. Configuring Nginx..."
cp deploy-scripts/nginx.conf /etc/nginx/sites-available/stu-alumni
ln -sf /etc/nginx/sites-available/stu-alumni /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Configure Supervisor
echo "8. Configuring Supervisor..."
cp deploy-scripts/supervisor.conf /etc/supervisor/conf.d/stu-alumni.conf

# Setup SSL (Let's Encrypt)
echo "9. Setting up SSL (optional)..."
# Uncomment the following lines if you have a domain and want SSL
# apt install -y certbot python3-certbot-nginx
# certbot --nginx -d alumni.stu.edu.gh

# Restart services
echo "10. Restarting services..."
systemctl restart nginx
systemctl restart php8.1-fpm
systemctl restart supervisor
systemctl enable nginx php8.1-fpm supervisor

echo ""
echo "âœ… Initial setup completed!"
echo ""
echo "Next steps:"
echo "1. Copy your application code to /var/www/stu-alumni"
echo "2. Copy .env.example to .env and configure your settings"
echo "3. Run: composer install --no-dev --optimize-autoloader"
echo "4. Run: php artisan key:generate"
echo "5. Run: php artisan migrate --seed"
echo "6. Run: npm install && npm run build"
echo "7. Set up cron jobs from deploy-scripts/crontab.txt"
echo "8. Test the application at https://alumni.stu.edu.gh"
