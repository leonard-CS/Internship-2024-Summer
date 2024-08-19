#!/bin/bash

# Function to create a timestamp request
create_timestamp_request() {
    local file=$1
    local tsq_file="${file}.tsq"
    local tsr_file="${file}.tsr"

    # 1. Create a tsq file (SHA 512)
    openssl ts -query -data "$file" -no_nonce -sha512 -out "$tsq_file"

    # 2. cURL Time Stamp Request Input (HTTP / HTTPS)
    curl -H "Content-Type: application/timestamp-query" --data-binary @"$tsq_file" https://freetsa.org/tsr > "$tsr_file"

    echo "Timestamp request complete: $tsq_file and $tsr_file created."
}

# Function to perform stamping
perform_stamping() {
    local file=$1
    local print_info=$2
    create_timestamp_request "$file"
}

if [ "$#" -ne 1 ]; then
    echo "Usage: $0 <file>"
    exit 1
fi

file=$1

perform_stamping "$file"
