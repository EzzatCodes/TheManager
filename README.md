# The Manager

The Manager is a **desktop-first enterprise management system** designed to manage distributed employees with real-time presence tracking and centralized control.

Although the backend is powered by a modern web stack, the system is **not exposed as a public website**.  
Access is restricted exclusively to a desktop application built with Electron.

---

## Overview

The Manager was built to solve a common enterprise problem:  
**How do you manage employees across multiple locations without exposing your system publicly?**

The solution is a hybrid architecture:
- A secure Laravel backend hosted on the cloud
- A controlled Electron desktop client as the only access point
- Real-time communication for live presence updates

From the user’s perspective, this is a **native desktop application**, not a website.

---

## Architecture

- **Backend:** Laravel (PHP)
- **Frontend:** Blade + Vite
- **Desktop Client:** Electron (Windows)
- **Database:** MySQL
- **Real-Time Engine:** Pusher
- **Hosting:** Cloud / VPS (Hostinger, DigitalOcean, Hetzner-ready)

The backend handles business logic and data persistence, while the Electron client provides a secure and controlled interface for employees.
This separation allows independent scaling, secure access control, and long-term maintainability.

---

## Why Desktop, Not Web?

This project intentionally avoids being a traditional public website in order to:

- Prevent unauthorized browser access
- Enforce controlled usage through a desktop client only
- Allow deeper system-level features such as:
  - Auto-launch and background presence
  - System tray integration
  - Native OS controls
  - Future offline detection

Even if the backend URL is known, direct browser access is blocked.

---

## Real-Time Features

- Live online/offline employee presence
- Instant updates across all connected clients
- Centralized real-time state using Pusher
- Production-ready (no `npm run dev` required on user machines)

---

## Security Model

- Backend routes are protected using custom Laravel middleware
- Requests must include specific desktop headers
- Browser-based access is rejected
- Only Electron clients can communicate with the backend

This ensures the system behaves like a **private internal application**, not a public service.

---

## Local Development

### Backend

```bash
composer install
php artisan migrate
php artisan serve
```

-- Frontend Assets
- npm install
- npm run dev

-- Desktop Application
- npm install
- npm start


-- Production Build (Desktop)
- npm run build
- npm run dist
---

## Use Case

- PhenixManager is suitable for organizations that:

- Manage employees across multiple locations

- Require real-time visibility

- Prefer desktop-controlled access

- Need strong separation between public and internal systems


## Status

This project is production-oriented and architected for scalability:

- VPS-ready

- Real-time enabled

- Desktop-only access model

- Clean separation of concerns

