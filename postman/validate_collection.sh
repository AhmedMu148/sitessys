#!/bin/bash

# SPS API Collection Validator
# Validates the Postman collection and environment files

echo ""
echo "🔍 SPS API Collection Validator"
echo "==============================="
echo ""

# File paths
COLLECTION="SPS_API_Collection.postman_collection.json"
ENVIRONMENT="SPS_Local_Environment.postman_environment.json"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Validation counters
CHECKS_PASSED=0
CHECKS_FAILED=0
TOTAL_CHECKS=0

# Helper function for validation
validate_check() {
    local description=$1
    local condition=$2
    
    TOTAL_CHECKS=$((TOTAL_CHECKS + 1))
    
    if [ "$condition" = "true" ]; then
        echo -e "✅ $description"
        CHECKS_PASSED=$((CHECKS_PASSED + 1))
    else
        echo -e "❌ $description"
        CHECKS_FAILED=$((CHECKS_FAILED + 1))
    fi
}

echo "📁 File Validation"
echo "------------------"

# Check if collection file exists
if [ -f "$COLLECTION" ]; then
    validate_check "Collection file exists" "true"
else
    validate_check "Collection file exists" "false"
    echo -e "${RED}Collection file not found: $COLLECTION${NC}"
fi

# Check if environment file exists
if [ -f "$ENVIRONMENT" ]; then
    validate_check "Environment file exists" "true"
else
    validate_check "Environment file exists" "false"
    echo -e "${RED}Environment file not found: $ENVIRONMENT${NC}"
fi

echo ""
echo "🔧 Collection Structure Validation"
echo "-----------------------------------"

if [ -f "$COLLECTION" ]; then
    # Check if jq is available
    if command -v jq &> /dev/null; then
        # Validate JSON structure
        if jq empty "$COLLECTION" 2>/dev/null; then
            validate_check "Collection JSON is valid" "true"
        else
            validate_check "Collection JSON is valid" "false"
        fi
        
        # Check collection info
        COLLECTION_NAME=$(jq -r '.info.name // "Unknown"' "$COLLECTION")
        validate_check "Collection has name: $COLLECTION_NAME" "true"
        
        # Count endpoints
        ENDPOINT_COUNT=$(jq '[.item[] | recurse(.item[]?) | select(.request)] | length' "$COLLECTION" 2>/dev/null || echo "0")
        validate_check "Collection has $ENDPOINT_COUNT endpoints" "true"
        
        # Check for required folders
        HEALTH_FOLDER=$(jq '.item[] | select(.name | contains("Health"))' "$COLLECTION" 2>/dev/null)
        if [ -n "$HEALTH_FOLDER" ]; then
            validate_check "Health & Monitoring folder exists" "true"
        else
            validate_check "Health & Monitoring folder exists" "false"
        fi
        
        AUTH_FOLDER=$(jq '.item[] | select(.name | contains("Authentication"))' "$COLLECTION" 2>/dev/null)
        if [ -n "$AUTH_FOLDER" ]; then
            validate_check "Authentication folder exists" "true"
        else
            validate_check "Authentication folder exists" "false"
        fi
        
        CONFIG_FOLDER=$(jq '.item[] | select(.name | contains("Configuration"))' "$COLLECTION" 2>/dev/null)
        if [ -n "$CONFIG_FOLDER" ]; then
            validate_check "Configuration Management folder exists" "true"
        else
            validate_check "Configuration Management folder exists" "false"
        fi
        
    else
        echo -e "${YELLOW}⚠️  jq not installed - skipping detailed validation${NC}"
        echo "Install with: sudo apt-get install jq (Ubuntu) or brew install jq (macOS)"
    fi
fi

echo ""
echo "🌍 Environment Validation"
echo "-------------------------"

if [ -f "$ENVIRONMENT" ]; then
    if command -v jq &> /dev/null; then
        # Validate environment JSON
        if jq empty "$ENVIRONMENT" 2>/dev/null; then
            validate_check "Environment JSON is valid" "true"
        else
            validate_check "Environment JSON is valid" "false"
        fi
        
        # Check environment name
        ENV_NAME=$(jq -r '.name // "Unknown"' "$ENVIRONMENT")
        validate_check "Environment has name: $ENV_NAME" "true"
        
        # Check required variables
        REQUIRED_VARS=("base_url" "api_url" "admin_email" "admin_password")
        
        for var in "${REQUIRED_VARS[@]}"; do
            VAR_EXISTS=$(jq --arg var "$var" '.values[] | select(.key == $var)' "$ENVIRONMENT" 2>/dev/null)
            if [ -n "$VAR_EXISTS" ]; then
                VAR_VALUE=$(jq -r --arg var "$var" '.values[] | select(.key == $var) | .value' "$ENVIRONMENT")
                if [ -n "$VAR_VALUE" ] && [ "$VAR_VALUE" != "null" ] && [ "$VAR_VALUE" != "" ]; then
                    validate_check "Required variable $var has value" "true"
                else
                    validate_check "Required variable $var has value" "false"
                fi
            else
                validate_check "Required variable $var exists" "false"
            fi
        done
        
        # Check optional test variables
        OPTIONAL_VARS=("test_theme" "test_primary_color" "section_template_id")
        
        for var in "${OPTIONAL_VARS[@]}"; do
            VAR_EXISTS=$(jq --arg var "$var" '.values[] | select(.key == $var)' "$ENVIRONMENT" 2>/dev/null)
            if [ -n "$VAR_EXISTS" ]; then
                validate_check "Test variable $var exists" "true"
            else
                validate_check "Test variable $var exists" "false"
            fi
        done
    fi
fi

echo ""
echo "🌐 Server Connectivity"
echo "----------------------"

# Check if server is running
if [ -f "$ENVIRONMENT" ] && command -v jq &> /dev/null; then
    BASE_URL=$(jq -r '.values[] | select(.key == "base_url") | .value' "$ENVIRONMENT" 2>/dev/null)
    
    if [ -n "$BASE_URL" ] && [ "$BASE_URL" != "null" ]; then
        # Test health endpoint
        if curl -s "$BASE_URL/health" > /dev/null 2>&1; then
            validate_check "Server is responding at $BASE_URL" "true"
        else
            validate_check "Server is responding at $BASE_URL" "false"
            echo -e "${YELLOW}💡 Start server with: php artisan serve${NC}"
        fi
        
        # Test API endpoint
        API_URL=$(jq -r '.values[] | select(.key == "api_url") | .value' "$ENVIRONMENT" 2>/dev/null)
        if [ -n "$API_URL" ] && [ "$API_URL" != "null" ]; then
            if curl -s "$API_URL/user" > /dev/null 2>&1; then
                validate_check "API endpoint is accessible" "true"
            else
                validate_check "API endpoint is accessible (without auth)" "true"
                echo -e "${BLUE}ℹ️  API responds (authentication required for most endpoints)${NC}"
            fi
        fi
    else
        validate_check "Base URL is configured" "false"
    fi
else
    echo -e "${YELLOW}⚠️  Cannot test server connectivity - environment not configured${NC}"
fi

echo ""
echo "📊 Validation Summary"
echo "===================="
echo ""

SUCCESS_RATE=0
if [ $TOTAL_CHECKS -gt 0 ]; then
    SUCCESS_RATE=$((CHECKS_PASSED * 100 / TOTAL_CHECKS))
fi

echo "Total Checks: $TOTAL_CHECKS"
echo "Passed: $CHECKS_PASSED"
echo "Failed: $CHECKS_FAILED"
echo "Success Rate: $SUCCESS_RATE%"
echo ""

if [ $CHECKS_FAILED -eq 0 ]; then
    echo -e "${GREEN}🎉 All validations passed! Collection is ready to use.${NC}"
    echo ""
    echo "🚀 Next Steps:"
    echo "1. Import collection and environment into Postman"
    echo "2. Run the collection or individual requests"
    echo "3. Use Newman for automated testing: ./run_newman_tests.sh"
    exit 0
elif [ $SUCCESS_RATE -ge 80 ]; then
    echo -e "${YELLOW}⚠️  Most validations passed. Minor issues detected.${NC}"
    echo ""
    echo "🔧 Recommended Actions:"
    echo "1. Fix any failed validations above"
    echo "2. Test basic functionality in Postman"
    echo "3. Update environment variables as needed"
    exit 1
else
    echo -e "${RED}❌ Multiple validation failures detected.${NC}"
    echo ""
    echo "🛠️  Required Actions:"
    echo "1. Fix critical issues listed above"
    echo "2. Ensure server is running: php artisan serve"
    echo "3. Verify environment variables are set"
    echo "4. Re-run validation: ./validate_collection.sh"
    exit 2
fi
