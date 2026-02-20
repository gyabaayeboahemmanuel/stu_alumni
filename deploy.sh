#!/bin/bash

#######################################
# STUALUMNI DEPLOYMENT SCRIPT
# Run this once on your production server
# Usage: bash deploy.sh
#######################################

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
APP_DIR="/home/gekymedia/web/stualumni.gekymedia.com/public_html"
BRANCH="master"               # Change this to your production branch

echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}   STUALUMNI DEPLOYMENT SCRIPT${NC}"
echo -e "${BLUE}========================================${NC}"
echo ""

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    if [ -d "$APP_DIR" ]; then
        cd "$APP_DIR"
    else
        echo -e "${RED}Error: Not in Laravel project directory and APP_DIR not found${NC}"
        echo -e "${YELLOW}Please edit this script and set APP_DIR to your project path${NC}"
        exit 1
    fi
fi

echo -e "${GREEN}[1/9]${NC} Enabling maintenance mode..."
php artisan down --message="Upgrading... Please check back in a few minutes." --retry=60 || true

echo -e "${GREEN}[2/9]${NC} Pulling latest changes from git..."
git fetch origin
git reset --hard origin/$BRANCH

echo -e "${GREEN}[3/9]${NC} Installing/updating Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo -e "${GREEN}[4/9]${NC} Running database migrations..."
php artisan migrate --force

echo -e "${GREEN}[5/9]${NC} Clearing and rebuilding caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo -e "${GREEN}[6/9]${NC} Caching for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo -e "${GREEN}[7/9]${NC} Linking storage..."
php artisan storage:link || true

echo -e "${GREEN}[8/9]${NC} Setting permissions..."
# Adjust these based on your server user (www-data, nginx, apache, etc.)
if [ -d "storage" ]; then
    chmod -R 775 storage
    chmod -R 775 bootstrap/cache
    # Uncomment and adjust for your web server user:
    # chown -R www-data:www-data storage bootstrap/cache
fi

echo -e "${GREEN}[9/9]${NC} Disabling maintenance mode..."
php artisan up

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}   DEPLOYMENT COMPLETED SUCCESSFULLY!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}Post-deployment checklist:${NC}"
echo -e "  ✓ Check website is accessible"
echo -e "  ✓ Test critical functionality"
echo -e "  ✓ Monitor error logs: tail -f storage/logs/laravel.log"
echo ""
