#  Project VitalLeak: Containerized CTF Playbox

![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?logo=docker&logoColor=white)
![CTFd](https://img.shields.io/badge/Scoring-CTFd-red)
![Database](https://img.shields.io/badge/Database-MariaDB-003545?logo=mariadb&logoColor=white)
![Platform](https://img.shields.io/badge/Platform-Windows%20%7C%20Linux%20%7C%20macOS-lightgrey)

**Project VitalLeak** is a fully containerized Capture The Flag (CTF) penetration testing environment that simulates a healthcare data breach. Developed as an academic project for **SLIIT**, it is portable, safe to run locally, and deployable with a single command, with no manual configuration required.

---

##  Table of Contents

- [Overview](#-overview)
- [Architecture](#️-architecture)
- [Challenge Stages](#-challenge-stages)
- [Prerequisites](#️-prerequisites)
- [Quick Start](#-quick-start)
- [Accessing the Environment](#-accessing-the-environment)
- [Reset Mechanism](#-reset-mechanism)
- [Developer / Admin Guide](#️-developer--admin-guide)
- [Project Structure](#-project-structure)
- [Troubleshooting](#-troubleshooting)
- [Security Notice](#-security-notice)
- [Team and Credits](#-team-and-credits)

---

##  Overview

Players follow the story of a healthcare organization whose patient data has leaked. Across six progressive stages, they investigate the breach the way a real penetration tester would: from open-source reconnaissance to full root compromise.

**Key features**

-  **One-command deployment** with Docker Compose
-  **Plug-and-play scoring.** A master `ctfd_seed.sql` dump pre-loads all challenges, flags, hints, and user accounts
-  **Isolated by design.** Vulnerable targets cannot reach the scoring backend or the host
-  **Instant reset.** Rebuild broken challenge containers without losing player progress
-  **Multi-discipline.** Covers OSINT, forensics, web, networking, cryptography, and system security

---

##  Architecture

The environment is segmented into two isolated layers using Docker Compose:

| Layer | Components | Purpose |
|---|---|---|
| **Control Layer** | CTFd dashboard, MariaDB | Scoring, user accounts, challenge delivery |
| **Vulnerable Challenge Layer** | Legacy web portal, forensic/SSH server, image host | Intentionally misconfigured targets for players |

This segmentation, combined with Docker hardening controls (such as `cap_drop` and `SYS_CHROOT`), ensures that players exploiting the vulnerable services **cannot pivot into the scoring backend or compromise the host machine**.

```
┌──────────────────────────── Host Machine ────────────────────────────┐
│                                                                      │
│  ┌──────── Control Layer ────────┐  ┌── Vulnerable Challenge Layer ─┐│
│  │  CTFd (:80)  ◄──►  MariaDB    │  │  Legacy Portal  (:8080)       ││
│  │  Scoring & Accounts           │  │  Image Hosting  (:8081)       ││
│  │                               │  │  Forensics SSH  (:2222)       ││
│  └───────────────────────────────┘  └───────────────────────────────┘│
│              ▲ isolated networks — no cross-layer access ▲           │
└──────────────────────────────────────────────────────────────────────┘
```

---

## 🚩 Challenge Stages

| # | Stage | Category | Description | Tools |
|---|---|---|---|---|
| 1 | **OSINT** | Reconnaissance | Public digital footprint and Git commit history analysis | Browser, Git |
| 2 | **Forensics** | Steganography | Image steganography and hidden payload extraction | steghide, exiftool, strings |
| 3 | **Web Security** | Exploitation | SQL injection authentication bypass on a legacy portal | Browser, Burp Suite |
| 4 | **Networking** | Traffic Analysis | Unencrypted FTP packet capture (PCAP) analysis | Wireshark |
| 5 | **Cryptography** | Crypto | Reversing XOR ciphers and Base64 encoding | CyberChef |
| 6 | **System Security** | Privilege Escalation | SUID-based Linux privilege escalation to `root` | SSH, Linux CLI |

> Tool suggestions are indicative. Players may use any tools they prefer.

---

##  Prerequisites

| Requirement | Details |
|---|---|
| **Operating System** | Windows (with WSL2), Linux (Ubuntu / Pop!_OS), or macOS |
| **Software** | Docker Engine and Docker Compose |
| **Required Ports** | `80` (CTFd), `8080` (Stage 3 Web), `8081` (Image Hosting), `2222` (Stage 6 SSH) |
| **Player Tools** | Wireshark (Stage 4), CyberChef (Stage 5) |

Make sure none of the required ports are already in use before deploying.

---

##  Quick Start

Because the project ships with a master `ctfd_seed.sql` database dump, no manual configuration is needed.

**1. Clone the repository**

```bash
git clone https://github.com/Pulina69/Project-VitalLeak-penestration-testing-docker-CTF-playbox.git
cd Project-VitalLeak-penestration-testing-docker-CTF-playbox
```

**2. Deploy the environment**

```bash
docker-compose up -d --build
```

>  Docker builds the vulnerable containers from scratch while MariaDB ingests the SQL seed file in the background. Allow a minute or two on first launch.

**3. Verify that everything is running**

```bash
docker-compose ps
```

**4. Shut down when finished**

```bash
docker-compose down
```

---

##  Accessing the Environment

| Service | Address | Notes |
|---|---|---|
| **CTFd Dashboard** | http://localhost:80 | Admin login: `admin` / `admin123` (or your configured credentials) |
| **Stage 3 Web Portal** | http://localhost:8080 | Legacy portal target |
| **Image Hosting** | http://localhost:8081 | Stage 2 assets |
| **Stage 6 SSH Server** | `ssh aurastaff@localhost -p 2222` | Privilege escalation target |

>  Change the default admin password if the environment is used beyond a local demo.

---

##  Reset Mechanism

During a CTF, players may accidentally break the vulnerable services, delete critical files, or corrupt the system while exploiting it. The reset mechanism safely wipes and rebuilds **only the vulnerable containers**. The CTFd scoring database and player progress remain untouched.

### Windows

Open Command Prompt or PowerShell, navigate to the project directory, and run:

```bat
.\reset_vital.bat
```

The script targets the legacy portal and forensic containers, destroys them, and rebuilds clean instances from their Dockerfiles in seconds.

### Linux / macOS

Run the equivalent Docker Compose command:

```bash
docker-compose up -d --force-recreate --build stg_03_legacy_portal stg_06_forensics
```

---

##  Developer / Admin Guide

### Regenerating the PCAP (Stages 4 and 5)

If you modify the flags or SSH credentials, regenerate `capture.pcap` using the provided Python script. To keep your host clean, run it in a temporary Docker container.

**Windows (Command Prompt)**

```bat
docker run --rm -v "%cd%:/app" -w /app python:3.9-slim /bin/bash -c "pip install scapy && python generate_pcap.py"
```

**Linux / macOS / PowerShell**

```bash
docker run --rm -v "${PWD}:/app" -w /app python:3.9-slim /bin/bash -c "pip install scapy && python generate_pcap.py"
```

The script forges the TCP handshake, encrypts the payload with XOR/Base64, simulates the FTP conversation, and writes the new `capture.pcap` into your project folder.

---

##  Project Structure

```
Project-VitalLeak/
├── docker-compose.yml        # Orchestrates control and challenge layers
├── ctfd_seed.sql             # Pre-loaded CTFd challenges, flags, hints, users
├── reset_vital.bat           # Windows reset script for vulnerable containers
├── generate_pcap.py          # Scapy script that builds capture.pcap
├── capture.pcap              # Network capture for Stages 4 and 5
└── ...                       # Dockerfiles and challenge assets per stage
```

> Adjust this tree to match your actual repository layout.

---

##  Troubleshooting

| Problem | Likely Cause | Fix |
|---|---|---|
| `port is already allocated` | Another service is using 80, 8080, 8081, or 2222 | Stop the conflicting service or change the host port mapping in `docker-compose.yml` |
| CTFd shows no challenges | MariaDB seed import still running | Wait a minute, then refresh. Check `docker-compose logs` |
| Stage 3 or Stage 6 behaves oddly | Container was modified during exploitation | Run the [reset script](#-reset-mechanism) |
| Docker commands fail on Windows | WSL2 or Docker Desktop not running | Start Docker Desktop and confirm WSL2 integration is enabled |

To fully rebuild everything from scratch:

```bash
docker-compose down -v
docker-compose up -d --build
```

>  The `-v` flag also deletes the database volume, which resets all player progress.

---

##  Security Notice

- All vulnerabilities in this project are **intentional** and exist for teaching purposes.
- Run the environment on a **local or isolated network only**.
- Do **not** deploy on public servers or cloud instances without strict network controls.
- Techniques demonstrated here must only be used against systems you own or have explicit permission to test.
- The authors accept no responsibility for misuse.

---

##  Team and Credits

Developed by **Pethvan p.p | Aluthge M.N | Adithya E.A.D.D.D | Shanthoshika S** for **SLIIT** as part of the Penetration Testing module.

Built with: [Docker](https://www.docker.com/) · [CTFd](https://ctfd.io/) · [MariaDB](https://mariadb.org/) · 

---

