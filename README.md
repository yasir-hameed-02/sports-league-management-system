# Sports League Management System

A full CRUD system for managing a sports league — teams, players, coaches, venues, seasons, matches, and standings — built **twice** as part of coursework for **CS-304 Database Systems**, HITEC University, Taxila (BS Cyber Security, 4th Semester), to compare a traditional relational-database approach against a client-side JSON/localStorage data layer.

This repository contains both implementations as separate subfolders.

## Versions

| | [`php-mysql-version/`](./php-mysql-version) | [`json-localstorage-version/`](./json-localstorage-version) |
|---|---|---|
| **Type** | CCP (individual) | Semester project |
| **Author(s)** | Yasir Hameed (24-CyS-002) | Yasir Hameed (24-CyS-002) |
| **Backend** | PHP (mysqli) | None — pure client-side |
| **Data storage** | MySQL (relational, normalized to 3NF) | Browser `localStorage` (JSON documents) |
| **Frontend** | Plain PHP + inline CSS (dark glassmorphism theme) | React 19 + TypeScript + Tailwind CSS 4 |
| **Build tool** | None (runs directly on PHP server) | Vite |
| **Tables / modules** | 7 (`coaches`, `teams`, `players`, `venues`, `seasons`, `matches`, `standings`) | 20 (adds scores, game logs, statistics, player profiles, injuries, awards, equipment, sponsors, posters, tickets, fan registration, references, etc.) |
| **Relationships** | Real SQL foreign keys + `JOIN` queries | Resolved in-app via foreign-key lookups on JSON records |
| **Persistence scope** | Server-side, shared across users | Per-browser only (data is lost if `localStorage` is cleared) |
| **Setup** | XAMPP / any PHP+MySQL stack | `npm install && npm run dev` |

## Why two versions?

The PHP/MySQL version was built as an individual CCP to demonstrate normalized relational schema design (UNF → 1NF → 2NF → 3NF), SQL joins, and a server-backed CRUD portal. The JSON/localStorage version was a separate team project exploring the same domain with a modern frontend stack and a much larger, more realistic module set (20 entities), using client-side storage instead of a real database.

## Repository structure

```
sports-league-management-system/
├── php-mysql-version/
│   ├── sports_league/          # PHP pages (CRUD for all 7 tables)
│   ├── sports_league_system.sql
│   └── docs/CCP_Report.pdf
├── json-localstorage-version/
│   ├── src/                    # React + TypeScript source
│   ├── index.html, package.json, vite.config.ts, tsconfig.json
│   └── docs/                   # Proposal + project report
└── README.md
```

See each subfolder's own README for setup instructions, features, and file details.

---
CS-304 Database Systems — Department of Computer Science, HITEC University, Taxila
