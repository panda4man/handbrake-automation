#!/bin/bash

# Arguments
INPUT_FILE=$1
OUTPUT_FILE=$2
PRESET=$3
API_URL=$4
JOB_ID=$5
PRESET_JSON=$6

# Define a log file for the process output
LOG_FILE="/Users/aclinton/Dev/Personal/Valet/handbrake-automation/storage/app/private/compression-logs/compression_$JOB_ID.log"

# Build the HandBrakeCLI command
if [ -n "$PRESET_JSON" ]; then
    /opt/homebrew/bin/HandBrakeCLI \
        -i "$INPUT_FILE" \
        -o "$OUTPUT_FILE" \
        --preset-import-file "$PRESET_JSON" \
        -Z "$PRESET" \
        --encoder x264 >> "$LOG_FILE" 2>&1 &
else
    /opt/homebrew/bin/HandBrakeCLI \
        -i "$INPUT_FILE" \
        -o "$OUTPUT_FILE" \
        -Z "$PRESET" \
        --encoder x264 >> "$LOG_FILE" 2>&1 &
fi

# Capture the PID of the HandBrakeCLI process
PID=$!

# Wait for the HandBrakeCLI process to complete
wait $PID
EXIT_STATUS=$?

# Check the exit status of the HandBrakeCLI command
if [ $EXIT_STATUS -eq 0 ]; then
    STATUS="success"
else
    STATUS="failure"
fi

# Notify the Laravel API about the process completion
curl -X POST "$API_URL" \
    -H "Content-Type: application/json" \
    -d '{
        "job_id": '"$JOB_ID"',
        "status": "'"$STATUS"'"
    }'
