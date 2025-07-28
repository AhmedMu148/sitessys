#!/bin/bash

# Quick Headers & Footers Functionality Test
# Tests the fixed system with admin login: admin@seo.com / seopass123

set -e

BASE_URL="http://127.0.0.1:8000"
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}=== Quick Headers & Footers Test ===${NC}"
echo ""

# Test 1: Check if server is running
echo -e "${BLUE}1. Testing Server Status${NC}"
if curl -s --max-time 5 "$BASE_URL" > /dev/null; then
    echo -e "${GREEN}✓ Laravel server is running${NC}"
else
    echo -e "${RED}✗ Laravel server is not accessible${NC}"
    exit 1
fi

# Test 2: Check admin login page
echo -e "${BLUE}2. Testing Admin Login Page${NC}"
LOGIN_PAGE=$(curl -s "$BASE_URL/admin/login")
if echo "$LOGIN_PAGE" | grep -q "login\|Login"; then
    echo -e "${GREEN}✓ Admin login page accessible${NC}"
else
    echo -e "${RED}✗ Admin login page not accessible${NC}"
fi

# Test 3: Test database state
echo -e "${BLUE}3. Testing Database State${NC}"
cd /home/jimmy/Desktop/adminkit-main/SPS
DB_STATE=$(php artisan tinker --execute="
use App\Models\TplLayout;
use App\Models\Site;
echo 'Templates: ' . TplLayout::count() . ' (Global: ' . TplLayout::where('tpl_id', 'like', 'global-%')->count() . ')';
echo 'Sites: ' . Site::count();
echo 'Sites with headers: ' . Site::whereNotNull('active_header_id')->count();
echo 'Sites with footers: ' . Site::whereNotNull('active_footer_id')->count();
" 2>/dev/null | grep -E "Templates|Sites")

if [ ! -z "$DB_STATE" ]; then
    echo -e "${GREEN}✓ Database state:${NC}"
    echo "$DB_STATE" | while read line; do
        echo -e "${YELLOW}  $line${NC}"
    done
else
    echo -e "${RED}✗ Database test failed${NC}"
fi

# Test 4: Check frontend rendering
echo -e "${BLUE}4. Testing Frontend Rendering${NC}"
FRONTEND=$(curl -s "$BASE_URL/" | head -20)
if echo "$FRONTEND" | grep -q "<!DOCTYPE html"; then
    echo -e "${GREEN}✓ Frontend renders HTML correctly${NC}"
else
    echo -e "${RED}✗ Frontend rendering issues${NC}"
fi

# Test 5: Check template files existence
echo -e "${BLUE}5. Testing File Structure${NC}"
if [ -f "app/Http/Controllers/Admin/HeaderFooterController.php" ]; then
    echo -e "${GREEN}✓ HeaderFooterController exists${NC}"
else
    echo -e "${RED}✗ HeaderFooterController missing${NC}"
fi

if [ -f "resources/views/admin/layouts/headers-footers.blade.php" ]; then
    echo -e "${GREEN}✓ Headers-footers view exists${NC}"
else
    echo -e "${RED}✗ Headers-footers view missing${NC}"
fi

if [ -f "public/css/admin/headers-footers.css" ]; then
    echo -e "${GREEN}✓ Headers-footers CSS exists${NC}"
else
    echo -e "${RED}✗ Headers-footers CSS missing${NC}"
fi

if [ -f "public/js/admin/headers-footers.js" ]; then
    echo -e "${GREEN}✓ Headers-footers JS exists${NC}"
else
    echo -e "${RED}✗ Headers-footers JS missing${NC}"
fi

# Test 6: Service classes test
echo -e "${BLUE}6. Testing Service Classes${NC}"
SERVICES_TEST=$(php artisan tinker --execute="
use App\Services\GlobalTemplateService;
use App\Services\NavigationService;
echo 'GlobalTemplateService: ' . (class_exists('App\Services\GlobalTemplateService') ? 'OK' : 'Missing');
echo 'NavigationService: ' . (class_exists('App\Services\NavigationService') ? 'OK' : 'Missing');
" 2>/dev/null | grep -E "Service:")

if echo "$SERVICES_TEST" | grep -q "OK"; then
    echo -e "${GREEN}✓ Service classes available${NC}"
else
    echo -e "${YELLOW}? Service classes test unclear${NC}"
fi

echo ""
echo -e "${GREEN}🎉 Quick test completed!${NC}"
echo -e "${YELLOW}📝 To test full functionality:${NC}"
echo -e "${YELLOW}   1. Login: http://127.0.0.1:8000/admin/login${NC}"
echo -e "${YELLOW}   2. Credentials: admin@seo.com / seopass123${NC}"
echo -e "${YELLOW}   3. Navigate: Headers & Footers Management${NC}"
echo ""
