# Sigstore Timestamp Authority Setup Guide
Sigstore Hompage: [[link]](https://www.sigstore.dev)
GitHub Homepage: [[link]](https://github.com/sigstore/timestamp-authority)

## 1. Introduction
This document provides a step-by-step guide to set up a Timestamp Server. 

Trusted timestamping provides a timestamp record of when a document was created or modified. A timestamp authority creates signed timestamps using public key infrastructure.

## 2. Clone the Repository
First, clone the Timestamp Server repository from GitHub.
```
git clone https://github.com/sigstore/timestamp-authority.git
```

Navigate into the project directory:
```
cd timestamp-authority
```

## 3. Run the Server
To start the server, run:
```
docker-compose up
```

This will start the server in the foreground. If you want to run it in detached mode, use:
```
docker-compose up -d
```

## 4. Handling Certificates
The following shell script helps to manage certificates for the Timestamp Server. It downloads the certificate chain, splits it, and prepares the necessary files for verifying timestamp responses. [setup_certs.sh](setup_certs.sh)

Add the executable permission using the chmod command:
```
chmod +x setup_certs.sh
```

Run the script:
```
./setup_certs.sh
```

## 5. Using the Timestamp Server
Once the server is running, you can access it at `http://localhost:3000`

Here’s how to interact with the Timestamp Server using `OpenSSL` and `curl`:

### 5.1 Create a Timestamp Request
To create a timestamp request, use the following command:
```
openssl ts -query -data test.txt -cert -sha256 -out request.tsq
```

This generates a timestamp query file `request.tsq` for the data in `test.txt`.

### 5.2 Get Timestamp Response
Send the timestamp request to the server using `curl`:
```
curl -sSH "Content-Type: application/timestamp-query" --data-binary @request.tsq http://localhost:3000/api/v1/timestamp -o response.tsr
```

This sends the request and saves the timestamp response to `response.tsr`.

### 5.3 Verify the Timestamp Response
Verify the response using the root certificate and chain created earlier:
```
$ openssl ts -verify -in response.tsr -data test.txt -CAfile root.crt.pem -untrusted chain.crts.pem
```

This checks the authenticity of the timestamp response.

### 5.4 Inspect the Timestamp Response
To inspect the contents of the timestamp response, use:
```
openssl ts -reply -in response.tsr -text
```

This displays the timestamp response in a human-readable format.