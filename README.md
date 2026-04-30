# Conference Room Reservation System

A web-based conference room reservation system built with Laravel and Vue.js. 
Designed to make room booking within an organization by providing a centralized 
platform for managing rooms and reservations.

## Features

- Room management (Create, Read, Update, Delete)
- Reservation scheduling with conflict detection
- Booking validation to prevent overlapping reservations

## Tech Stack

- **Backend:** Laravel (PHP)
- **Frontend:** Vue.js
- **Database:** MySQL
- **Version Control:** Git / GitHub

## Status

!!! Currently in active development. Core CRUD operations and reservation 
logic implemented. Core business logic and Frontend UI in progress.

## Getting Started

### Prerequisites
- PHP >= 8.0
- Composer
- Node.js & npm
- MySQL

### Installation
```bash
git clone https://github.com/VanillaEclair/conference-room-reservation
cd the-repo-name
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
```
