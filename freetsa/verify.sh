#!/bin/bash

# Function to verify timestamp
verify_timestamp() {
    local file=$1
    local tsr_file="${file}.tsr"
    openssl ts -verify -data "$file" -in "$tsr_file" -CAfile cacert.pem -untrusted tsa.crt
}

if [ "$#" -ne 1 ]; then
    echo "Usage: $0 <file>"
    exit 1
fi

file=$1

# Get FreeTSA TSA certificate
wget -q https://freetsa.org/files/tsa.crt
# Get FreeTSA CA certificate
wget -q https://freetsa.org/files/cacert.pem

verify_timestamp "$file"

# Remove TSA & CA certificates
rm -f tsa.crt cacert.pem
