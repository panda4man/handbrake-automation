#!/bin/bash

# Arguments
INPUT_FILE=$1
OUTPUT_FILE=$2
PRESET=$3
API_URL=$4
JOB_ID=$5

# Define a log file for the process output
LOG_FILE="/Users/aclinton/Dev/Personal/Valet/handbrake-automation/storage/app/private/compression-logs/compression_$JOB_ID.log"

# Run HandBrakeCLI and redirect output to the log file
HandBrakeCLI -i "$INPUT_FILE" -o "$OUTPUT_FILE" --preset "$PRESET" > "$LOG_FILE" 2>&1 &
PID=$!

# Notify the Laravel API about the process start
curl -X POST "$API_URL" \
    -H "Content-Type: application/json" \
    -d '{
        "job_id": '"$JOB_ID"',
        "status": "running",
        "pid": '"$PID"'
    }'
