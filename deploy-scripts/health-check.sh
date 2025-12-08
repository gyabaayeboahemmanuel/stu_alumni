#!/bin/bash

# STU Alumni System Health Check Script
# Usage: ./health-check.sh

set -e

echo "ðŸ” STU Alumni System Health Check"
echo "================================="

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

check_status() {
    if [ $1 -eq 0 ]; then
        echo -e "${GREEN}âœ… $2${NC}"
    else
        echo -e "${RED}âŒ $2${NC}"
        return 1
    fi
}

# Check if .env exists
echo "1. Checking configuration..."
[ -f .env ] && check_status 0 ".env file exists" || check_status 1 ".env file missing"

# Check database connection
echo "2. Testing database connection..."
php artisan db:monitor > /dev/null 2>&1
check_status $? "Database connection"

# Check storage permissions
echo "3. Checking storage permissions..."
[ -w storage ] && check_status 0 "Storage directory writable" || check_status 1 "Storage directory not writable"
[ -w bootstrap/cache ] && check_status 0 "Bootstrap cache writable" || check_status 1 "Bootstrap cache not writable"

# Check required PHP extensions
echo "4. Checking PHP extensions..."
php -m | grep -q pdo_mysql && check_status 0 "PDO MySQL extension" || check_status 1 "PDO MySQL extension missing"
php -m | grep -q mbstring && check_status 0 "MBString extension" || check_status 1 "MBString extension missing"
php -m | grep -q xml && check_status 0 "XML extension" || check_status 1 "XML extension missing"
php -m | grep -q json && check_status 0 "JSON extension" || check_status 1 "JSON extension missing"

# Check queue workers
echo "5. Checking queue workers..."
php artisan queue:work --once > /dev/null 2>&1
check_status $? "Queue worker test"

# Check scheduled tasks
echo "6. Checking scheduler..."
php artisan schedule:list > /dev/null 2>&1
check_status $? "Scheduler configuration"

# Check disk space
echo "7. Checking disk space..."
DISK_USAGE=$(df . | awk 'NR==2 {print $5}' | sed 's/%//')
if [ $DISK_USAGE -lt 90 ]; then
    check_status 0 "Disk space adequate ($DISK_USAGE% used)"
else
    check_status 1 "Disk space low ($DISK_USAGE% used)"
fi

# Check memory usage
echo "8. Checking memory..."
MEM_FREE=$(free | awk 'NR==2{printf "%.0f", $4/$2 * 100}')
if [ $MEM_FREE -gt 10 ]; then
    check_status 0 "Memory adequate ($MEM_FREE% free)"
else
    check_status 1 "Memory low ($MEM_FREE% free)"
fi

echo ""
echo "ðŸ“Š Health Check Summary:"
echo "========================"

# Final overall status
if [ $? -eq 0 ]; then
    echo -e "${GREEN}ðŸŽ‰ All systems operational!${NC}"
else
    echo -e "${YELLOW}âš ï¸  Some issues detected. Please check above.${NC}"
fi

echo ""
echo "Next steps:"
echo "1. Review any errors above"
echo "2. Check application logs: tail -f storage/logs/laravel.log"
echo "3. Verify cron jobs are running"
echo "4. Test email functionality"
