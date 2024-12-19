#!/bin/bash

# Initialize an array to hold additional arguments
ADDITIONAL_ARGS=()

# Parse arguments
while [[ "$#" -gt 0 ]]; do
    case $1 in
        -u|--api_url) API_URL="$2"; shift ;;
        -j|--job_id) JOB_ID="$2"; shift ;;
        *) ADDITIONAL_ARGS+=("$1") ;; # Collect additional arguments
    esac
    shift
done

# Define a log file for the process output
LOG_FILE="/Users/aclinton/Dev/Personal/Valet/handbrake-automation/storage/app/private/compression-logs/compression_$JOB_ID.log"

# Build the HandBrakeCLI command
/opt/homebrew/bin/HandBrakeCLI "${ADDITIONAL_ARGS[@]}" >> "$LOG_FILE" 2>&1 &

# Capture the PID of the HandBrakeCLI process
PID=$!

STATUS="processing"

curl -X POST "$API_URL" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{
        "job_id": '"$JOB_ID"',
        "status": "'"$STATUS"'",
        "pid": '"$PID"'
    }' >> "$LOG_FILE" 2>&1

# Wait for the HandBrakeCLI process to complete
wait $PID
EXIT_STATUS=$?

# Determine the file size of the completed output file
if [ $EXIT_STATUS -eq 0 ]; then
    STATUS="success"
    if [ -f "$OUTPUT_FILE" ]; then
        FILE_SIZE_MB=$(du -m "$OUTPUT_FILE" | cut -f1) # Get file size in MB
    else
        FILE_SIZE_MB=null
    fi
else
    STATUS="failure"
    FILE_SIZE_MB=null
fi

# Notify the Laravel API about the process completion
curl -X POST "$API_URL" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{
        "job_id": '"$JOB_ID"',
        "status": "'"$STATUS"'",
        "file_size_after": '"$FILE_SIZE_MB"'
    }' >> "$LOG_FILE" 2>&1
