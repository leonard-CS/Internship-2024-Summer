#!/bin/bash

# Download the certificate chain from the timestamp server
curl http://localhost:3000/api/v1/timestamp/certchain > ts_chain.pem

# Split the certificate chain into individual certificates
csplit -s -f tmpcert- ts_chain.pem '/-----BEGIN CERTIFICATE-----/' '{*}'

# Remove the first part (which is usually not needed)
rm tmpcert-00

# Move the last certificate to a root certificate file
mv $(ls tmpcert-* | tail -1) root.crt.pem

# Concatenate the remaining certificates into a chain file
cat tmpcert-* > chain.crts.pem

# Clean up temporary files
rm tmpcert-*
