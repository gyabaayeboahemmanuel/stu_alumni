#!/bin/bash

# STU Alumni System Database Backup Script
# Usage: ./backup-db.sh [retention_days]

set -e

RETENTION_DAYS=${1:-30}
BACKUP_PATH="/var/backups/stu-alumni"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME=$(grep DB_DATABASE .env | cut -d '=' -f2)
DB_USER=$(grep DB_USERNAME .env | cut -d '=' -f2)
DB_PASS=$(grep DB_PASSWORD .env | cut -d '=' -f2)

echo "Starting database backup for STU Alumni System..."

# Create backup directory
mkdir -p $BACKUP_PATH

# Backup database
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_PATH/db_backup_$DATE.sql.gz

# Backup uploads directory
tar -czf $BACKUP_PATH/uploads_backup_$DATE.tar.gz -C storage/app/public .

echo "Backup completed:"
echo "Database: $BACKUP_PATH/db_backup_$DATE.sql.gz"
echo "Uploads: $BACKUP_PATH/uploads_backup_$DATE.tar.gz"

# Clean up old backups
find $BACKUP_PATH -name "*.sql.gz" -type f -mtime +$RETENTION_DAYS -delete
find $BACKUP_PATH -name "*.tar.gz" -type f -mtime +$RETENTION_DAYS -delete

echo "Old backups cleaned up (retention: $RETENTION_DAYS days)"
echo "Backup process completed successfully!"
