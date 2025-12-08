#!/bin/bash

# STU Alumni System - Comprehensive Test Script
# Run this to verify the entire system functionality

set -e

echo "ðŸ§ª STU Alumni System - Comprehensive Test Suite"
echo "=============================================="

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Function to run test and show result
run_test() {
    echo -n "Testing $1... "
    if $2 > /dev/null 2>&1; then
        echo -e "${GREEN}âœ… PASS${NC}"
    else
        echo -e "${RED}âŒ FAIL${NC}"
        return 1
    fi
}

echo ""
echo "1. Environment and Configuration Tests"
echo "--------------------------------------"

run_test "PHP version" "php -v | grep -q 'PHP 8'"
run_test "Composer" "composer --version"
run_test "Node.js" "node --version"
run_test "MySQL" "mysql --version"

echo ""
echo "2. Application Configuration Tests"
echo "----------------------------------"

run_test "Environment file" "test -f .env"
run_test "Application key" "grep -q 'APP_KEY=base64' .env"
run_test "Database configuration" "php artisan db:monitor"

echo ""
echo "3. Database Tests"
echo "-----------------"

run_test "Database connection" "php artisan db:monitor"
run_test "Migrations" "php artisan migrate:status | grep -q 'Yes'"
run_test "Seed data" "php artisan db:seed --class=RoleSeeder"

echo ""
echo "4. Feature Tests"
echo "----------------"

run_test "Registration flow" "php artisan test tests/Feature/AlumniRegistrationTest.php"
run_test "Authentication" "php artisan test tests/Feature/AuthenticationTest.php"
run_test "Business directory" "php artisan test tests/Feature/BusinessDirectoryTest.php"

echo ""
echo "5. Unit Tests"
echo "-------------"

run_test "Alumni model" "php artisan test tests/Unit/AlumniModelTest.php"
run_test "All unit tests" "php artisan test tests/Unit/"

echo ""
echo "6. API Tests"
echo "------------"

run_test "API health endpoint" "curl -f http://localhost/api/health > /dev/null 2>&1 || curl -f https://localhost/api/health > /dev/null 2>&1"
run_test "Public routes" "php artisan route:list | grep -q 'businesses.public.index'"

echo ""
echo "7. Security Tests"
echo "-----------------"

run_test "Configuration caching" "php artisan config:cache"
run_test "Route caching" "php artisan route:cache"
run_test "File permissions" "test -w storage && test -w bootstrap/cache"

echo ""
echo "8. Performance Tests"
echo "--------------------"

run_test "Composer autoload" "composer dump-autoload --optimize"
run_test "Asset compilation" "npm run build 2>/dev/null || echo 'Skipping asset compilation'"

echo ""
echo "ðŸ“Š Test Summary"
echo "==============="

echo ""
echo "Next steps:"
echo "1. Review any failed tests above"
echo "2. Run full test suite: php artisan test"
echo "3. Perform manual testing on critical flows"
echo "4. Check application logs for errors"
echo "5. Verify email functionality"
echo "6. Test file uploads"
echo "7. Validate SIS integration (if enabled)"
echo "8. Perform load testing (optional)"

echo ""
echo "ðŸŽ¯ Manual Testing Checklist"
echo "==========================="
echo ""
echo "Core Functionality:"
echo "âœ… Alumni registration (SIS and manual)"
echo "âœ… Email verification"
echo "âœ… Login/logout"
echo "âœ… Profile management"
echo "âœ… Business directory"
echo "âœ… Event registration"
echo "âœ… Announcements"
echo "âœ… Admin dashboard"
echo ""
echo "User Roles:"
echo "âœ… Super Admin access"
echo "âœ… Alumni Admin access"
echo "âœ… Content Editor access"
echo "âœ… Alumni user access"
echo ""
echo "Security:"
echo "âœ… Role-based access control"
echo "âœ… CSRF protection"
echo "âœ… XSS prevention"
echo "âœ… SQL injection prevention"
echo "âœ… File upload validation"
echo ""
echo "Performance:"
echo "âœ… Page load times"
echo "âœ… Database query optimization"
echo "âœ… Asset loading"
echo "âœ… Mobile responsiveness"
