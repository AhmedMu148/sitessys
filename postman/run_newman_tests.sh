#!/bin/bash

# SPS API Newman Test Runner
# Runs the Postman collection using Newman CLI with comprehensive reporting

echo ""
echo "🧪 SPS API Newman Test Runner"
echo "=============================="
echo ""

# Configuration
COLLECTION="SPS_API_Collection.postman_collection.json"
ENVIRONMENT="SPS_Local_Environment.postman_environment.json"
RESULTS_DIR="test-results"
REPORT_FILE="$RESULTS_DIR/sps-api-test-report.html"
JSON_REPORT="$RESULTS_DIR/sps-api-test-results.json"

# Check if Newman is installed
if ! command -v newman &> /dev/null; then
    echo "❌ Newman is not installed"
    echo "Install with: npm install -g newman newman-reporter-htmlextra"
    exit 1
fi

# Check if collection exists
if [ ! -f "$COLLECTION" ]; then
    echo "❌ Collection file not found: $COLLECTION"
    exit 1
fi

# Check if environment exists
if [ ! -f "$ENVIRONMENT" ]; then
    echo "❌ Environment file not found: $ENVIRONMENT"
    exit 1
fi

# Create results directory
mkdir -p "$RESULTS_DIR"

echo "📁 Collection: $COLLECTION"
echo "🌍 Environment: $ENVIRONMENT"
echo "📊 Results will be saved to: $RESULTS_DIR/"
echo ""

# Check if Laravel server is running
echo "🔍 Checking if Laravel server is running..."
if curl -s "http://127.0.0.1:8000/health" > /dev/null; then
    echo "✅ Laravel server is running at http://127.0.0.1:8000"
else
    echo "❌ Laravel server is not running!"
    echo "Start with: php artisan serve"
    exit 1
fi

echo ""
echo "🚀 Running Newman tests..."
echo ""

# Run Newman with comprehensive reporting
newman run "$COLLECTION" \
    -e "$ENVIRONMENT" \
    --reporters cli,json,htmlextra \
    --reporter-json-export "$JSON_REPORT" \
    --reporter-htmlextra-export "$REPORT_FILE" \
    --reporter-htmlextra-title "SPS API Test Report" \
    --reporter-htmlextra-browserTitle "SPS API Test Results" \
    --reporter-htmlextra-showOnlyFails false \
    --reporter-htmlextra-testPaging false \
    --reporter-htmlextra-hideRequestHeaders "Authorization" \
    --reporter-htmlextra-skipEnvironmentVars "admin_password,access_token" \
    --verbose \
    --bail false

# Check Newman exit code
NEWMAN_EXIT_CODE=$?

echo ""
echo "📊 Test Results:"
echo "=================="

if [ $NEWMAN_EXIT_CODE -eq 0 ]; then
    echo "✅ All tests passed successfully!"
    echo ""
    echo "📄 Reports generated:"
    echo "  - HTML Report: $REPORT_FILE"
    echo "  - JSON Report: $JSON_REPORT"
    
    # Extract test statistics from JSON report
    if [ -f "$JSON_REPORT" ]; then
        echo ""
        echo "📈 Test Statistics:"
        
        # Use jq if available for better JSON parsing
        if command -v jq &> /dev/null; then
            TOTAL=$(jq '.run.stats.requests.total' "$JSON_REPORT")
            FAILED=$(jq '.run.stats.requests.failed' "$JSON_REPORT")
            PASSED=$((TOTAL - FAILED))
            
            echo "  - Total Requests: $TOTAL"
            echo "  - Passed: $PASSED"
            echo "  - Failed: $FAILED"
            
            if [ "$FAILED" -eq 0 ]; then
                echo "  - Success Rate: 100%"
            else
                SUCCESS_RATE=$((PASSED * 100 / TOTAL))
                echo "  - Success Rate: $SUCCESS_RATE%"
            fi
        fi
    fi
    
    echo ""
    echo "🌐 Open HTML report in browser:"
    echo "  file://$(pwd)/$REPORT_FILE"
    
else
    echo "❌ Some tests failed (Exit code: $NEWMAN_EXIT_CODE)"
    echo ""
    echo "📄 Check reports for details:"
    echo "  - HTML Report: $REPORT_FILE"
    echo "  - JSON Report: $JSON_REPORT"
fi

echo ""
echo "💡 Next Steps:"
echo "1. Review the HTML report for detailed test results"
echo "2. Check any failed requests and their error details"
echo "3. Update environment variables if needed"
echo "4. Re-run specific tests using Postman UI"
echo ""

exit $NEWMAN_EXIT_CODE
