@echo off
:: Project VitalLeak - Windows Reset and Recovery
:: Purpose: Recreate the Stage 3 and Stage 6 challenge containers.
:: Scope: CTFd and MariaDB are not directly targeted by this script.
:: Verification: Check container status and application availability
:: after the reset completes.

echo Initiating Project VitalLeak Environment Reset...

:: Remove the existing Stage 3 and Stage 6 challenge containers
docker compose rm -s -f -v stg_03_legacy_portal stg_06_forensics

:: Rebuild and restart the challenge containers
docker compose up -d --build stg_03_legacy_portal stg_06_forensics

:: Remove dangling Docker images to reclaim disk space
docker image prune -f

echo Vulnerable stages reset successfully. CTFd data preserved.
pause