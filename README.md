# Project VitalLeak - CTF Play Box
**Team Members:** Pethvan P. P., Aluthge M.N., Adithya E.A.D.D.D, Shanthoshika S.

## Overview
A containerized CTF environment simulating a healthcare data breach. The platform uses Docker Compose to isolate intentionally vulnerable containers from the control layer and host network.

## System Requirements
- Ubuntu/Pop!_OS (Linux 64-bit)
- Docker Engine & Docker Compose
- Ports Required: 80, 8080, 8081, 2222

## Lecturer Deployment Guide
1. **Clone & Navigate:**
   `git clone [repository_link]`
   `cd Project-VitalLeak-penestration-testing-docker-CTF-playbox`

2. **Deploy the Environment:**
   Run the following command to build the networks and spin up all containers:
   `docker-compose up -d --build`

3. **Access the Box:**
   - **CTFd Dashboard:** `http://localhost:80`
   - **Stage 3 Web Portal:** `http://localhost:8080`
   - **Stage 6 Forensics:** `ssh aurastaff@localhost -p 2222`

4. **Reset Mechanism:**
   To reset the vulnerable stages without wiping student scores, execute the recovery script:
   `./reset_vital.sh`