#!/bin/bash

# STU Alumni System Deployment Script
# Usage: ./deploy.sh [environment]
# Example: ./deploy.sh production

set -e

ENVIRONMENT=${1:-production}
APP_NAME="stu-alumni"
APP_PATH="/var/www/$APP_NAME"
BACKUP_PATH="/var/backups/$APP_NAME"
DATE=$(date +%Y%m%d_%H%M%S)

echo "ðŸš€ Starting deployment for STU Alumni System to $ENVIRONMENT environment"
echo "=============================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to log messages
log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1"
}

warn() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1"
    exit 1
}

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    warn "Not running as root. Some operations might require sudo privileges."
fi

# Create backup directory
mkdir -p $BACKUP_PATH

log "Step 1: Maintenance mode ON"
php artisan down --message="System maintenance in progress. We'll be back shortly!"

log "Step 2: Backing up database"
mysqldump -u $(grep DB_USERNAME .env | cut -d '=' -f2) \
          -p$(grep DB_PASSWORD .env | cut -d '=' -f2) \
          $(grep DB_DATABASE .env | cut -d '=' -f2) > $BACKUP_PATH/db_backup_$DATE.sql

log "Step 3: Pull latest changes"
git pull origin main

log "Step 4: Install/update dependencies"
composer install --no-dev --optimize-autoloader

log "Step 5: Update npm dependencies (if needed)"
npm ci && npm run build

log "Step 6: Run database migrations"
php artisan migrate --force

log "Step 7: Clear and cache configuration"
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
php artisan view:clear
php artisan view:cache

log "Step 8: Cache events and services"
php artisan event:cache
php artisan optimize

log "Step 9: Update storage permissions"
chmod -R 755 storage
chmod -R 755 bootstrap/cache

log "Step 10: Restart queue workers"
php artisan queue:restart

log "Step 11: Maintenance mode OFF"
php artisan up

log "Step 12: Clear expired data"
php artisan auth:clear-resets
php artisan cache:clear

log "Step 13: Generate sitemap (if package installed)"
# php artisan sitemap:generate

log "Step 14: Send deployment notification"
# php artisan deploy:notification

echo "=============================================="
log "âœ… Deployment completed successfully!"
log "ðŸ“Š Application URL: $(grep APP_URL .env | cut -d '=' -f2)"
log "ðŸ’¾ Database backup: $BACKUP_PATH/db_backup_$DATE.sql"
echo ""

# Health check
log "Performing health check..."
curl -f $(grep APP_URL .env | cut -d '=' -f2)/api/health > /dev/null 2>&1 && \
    log "âœ… Health check passed" || \
    warn "âš ï¸  Health check failed - please verify manually"

echo ""
log "ðŸŽ‰ STU Alumni System is now live on $ENVIRONMENT!"
