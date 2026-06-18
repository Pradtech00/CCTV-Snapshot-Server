# 📷 CCTV Snapshot Server

> Lightweight CCTV Snapshot Monitoring Server built with **Bash**, **PHP**, **Nginx**, and **JavaScript** on **Debian 12**.

A lightweight monitoring system that automatically captures snapshots from IP Cameras, validates image quality, manages storage retention, performs health checks, and provides a real-time web dashboard without requiring a database or heavy frameworks.

---

## ✨ Features

- 📸 Automatic Snapshot Capture
- 🔐 HTTP Digest Authentication
- ✅ JPEG Image Validation
- 📏 Minimum Image Size Validation
- 🔄 Automatic Retry on Failure
- 🗂 Automatic Storage Management
- 🧹 Automatic Cleanup (Retention Policy)
- ❤️ Health Check Monitoring
- 📜 Activity Logging
- 🌐 Lightweight Web Dashboard
- ⚡ Real-time Dashboard (AJAX)
- 🚫 No Database Required
- 🚫 No Framework Required

---

## 🖥 Dashboard

> Dashboard Overview

<p align="center">
<img width="1357" height="654" alt="dashboard" src="https://github.com/user-attachments/assets/614beff0-2e6e-4d36-beea-88d3bbce4b86" />
</p>

The dashboard displays:

- Camera Information
- Server Time (Realtime)
- Storage Usage
- Daily Snapshot Counter
- Latest Snapshot Preview
- Activity Log
- System Status

---

## 🏗 System Architecture

```text
               +----------------------+
               |      IP Camera       |
               +----------+-----------+
                          |
                    HTTP Snapshot
                          |
                   snapshot.sh
                          |
          +---------------+---------------+
          |                               |
      images/                      snapshot.log
          |
     cleanup.sh
          |
   healthcheck.sh
          |
      status.php (REST API)
          |
      Web Dashboard
          |
        Browser
```

---

## 📂 Project Structure

```text
/opt/cctv
│
├── config
│   └── camera.conf
│
├── images
│   └── YYYY/MM/DD
│
├── logs
│   ├── snapshot.log
│   ├── cleanup.log
│   └── healthcheck.log
│
├── scripts
│   ├── snapshot.sh
│   ├── cleanup.sh
│   └── healthcheck.sh
│
└── web
    ├── api
    │   └── status.php
    │
    ├── assets
    │   ├── css
    │   └── js
    │
    ├── includes
    │   ├── config.php
    │   └── functions.php
    │
    └── index.php
```

---

## ⚙ Technologies

| Technology | Description |
|------------|-------------|
| Debian 12 | Operating System |
| Bash | Snapshot Engine |
| Curl | Camera Communication |
| Cron | Scheduler |
| PHP 8.2 | Dashboard Backend |
| Nginx | Web Server |
| JavaScript | Realtime Dashboard |
| HTML5/CSS3 | Frontend |

---

## 🚀 Main Components

### Snapshot Engine

Automatically captures snapshots from IP Cameras.

Features:

- HTTP Digest Authentication
- Retry Mechanism
- Timeout Configuration
- JPEG Validation
- Minimum File Size Validation

---

### Cleanup Engine

Automatically removes expired snapshots based on retention policy.

---

### Health Check

Continuously verifies:

- Configuration
- Image Directory
- Log Directory
- Latest Snapshot
- System Status

---

### Dashboard

Realtime monitoring dashboard displaying:

- Camera Name
- Camera IP
- Current Server Time
- Today's Snapshot Count
- Storage Usage
- Latest Snapshot Preview
- Activity Log

---

## 📡 REST API

Example endpoint:

```
GET /api/status.php
```

Example response:

```json
{
  "camera": "Main Gate Camera",
  "ip": "192.168.1.10",
  "snapshot_today": 24,
  "storage": "356 MB",
  "server_time": "2026-06-18 10:30:15",
  "image": "/images/2026/06/18/103000.jpg",
  "activity": [
    "...",
    "...",
    "..."
  ]
}
```

---

## 📋 Configuration

Main configuration file:

```
config/camera.conf
```

Example:

```bash
CAMERA_NAME="Main Gate"

CAMERA_IP="192.168.1.10"

CAMERA_PORT=80

CAMERA_USER="admin"

CAMERA_PASS="password"

SNAPSHOT_INTERVAL=4

RETENTION_DAYS=30
```

---

## 📅 Scheduled Tasks

Cron Jobs

```cron
0 1,8,12,16,20 * * * snapshot.sh
15 0 * * * cleanup.sh
*/10 * * * * healthcheck.sh
```

---

## 💡 Why This Project?

This project was developed to provide a lightweight CCTV monitoring solution suitable for:

- Small Office
- Factory
- Warehouse
- Laboratory
- School
- Home Server
- Mini PC
- LXC Container
- Virtual Machine

without requiring:

- Database
- Docker
- Large Frameworks

---

## 📈 Roadmap

### Version 1.0

- [x] Snapshot Engine
- [x] Automatic Cleanup
- [x] Health Check
- [x] REST API
- [x] Web Dashboard
- [x] Realtime Update

### Version 1.1

- [ ] Dynamic Camera Status
- [ ] Dark Mode
- [ ] Image Gallery
- [ ] Download Snapshot

### Version 2.0

- [ ] Multi Camera Support
- [ ] SQLite Database
- [ ] User Authentication
- [ ] Telegram Notification
- [ ] Email Notification

---

## 🤝 Contributions

Contributions are welcome.

Feel free to fork this repository and submit pull requests.

---

## 📄 License

MIT License

---

## 👨‍💻 Author

Developed as a personal infrastructure and monitoring project for learning, automation, and production-ready lightweight CCTV monitoring systems.
