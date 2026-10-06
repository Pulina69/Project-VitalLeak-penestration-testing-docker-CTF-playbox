#!/bin/bash
echo "Initiating Project VitalLeak Environment Reset..."

# Target only the vulnerable containers for destruction, wiping their volumes
docker-compose rm -s -f -v stg_03_legacy_portal stg_06_forensics

# Rebuild and restart the vulnerable containers from the base images
docker-compose up -d --build stg_03_legacy_portal stg_06_forensics

# Clean up dangling images to prevent drive exhaustion
docker image prune -f

echo "Vulnerable stages reset successfully. CTFd data preserved."