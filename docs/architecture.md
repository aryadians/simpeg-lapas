# Architecture Documentation

This document describes the high-level architecture of **SIMPEG Lapas**.

## Tech Stack
- **Framework:** Laravel 12.x
- **Frontend:** Livewire 4.x (TALL Stack: Tailwind, Alpine.js, Laravel, Livewire)
- **Database:** SQLite/MySQL/PostgreSQL
- **Real-time:** Alpine.js for client-side interactivity

## Key Components

### 1. Attendance System (Geofencing)
The attendance system uses browser-based Geolocation API to get the user's coordinates.
- **Verification:** The backend compares user coordinates with `OFFICE_LATITUDE` and `OFFICE_LONGITUDE` from the `.env` file using the Haversine formula.
- **Evidence:** Requires a base64 encoded selfie image captured via the camera.

### 2. Roster Scheduling
Roster generation logic is handled within the `Roster` and `Shift` models/services. It considers:
- Employee availability.
- Shift patterns.
- Fair distribution of duties.

### 3. Tukin Calculation
The system calculates performance allowances (Tukin) based on:
- **Late Arrivals:** Deductions are applied for every minute/hour late according to institutional rules.
- **Absences:** Unexcused absences result in significant deductions.
- **Output:** Reports are generated via `barryvdh/laravel-dompdf`.

## Data Flow
1. **User Interaction:** Livewire components handle user input and UI updates without full page reloads.
2. **Backend Logic:** Controllers and Livewire classes process business logic.
3. **Storage:** Data is persisted in the database, and files (selfies) are stored in `storage/app/public`.

## Security
- **Role-Based Access Control (RBAC):** Managed via middleware and Laravel's authorization gates/policies.
- **Data Encryption:** Sensitive information is encrypted using Laravel's encryption services.
