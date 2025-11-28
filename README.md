# Management System for Troubleshooting Report

A Laravel-based web application for managing device troubleshooting reports.

## Features

- User authentication (Registration and Login)
- Device troubleshooting report generation
- Report status management (On-going → Complete)
- PDF export with hash verification
- Email sending via Mailjet API
- Timestamp tracking (Created, Last Edited, Completed)
- Dashboard with recent reports
- Complete reports listing

## Requirements

- PHP >= 8.1
- Composer
- MySQL/MariaDB
- Node.js and NPM (for assets)

## Installation

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   ```
3. Copy `.env.example` to `.env`:
   ```bash
   copy .env.example .env
   ```
4. Generate application key:
   ```bash
   php artisan key:generate
   ```
5. Configure your database in `.env` file
6. Configure Mailjet API credentials in `.env`:
   - `MAILJET_API_KEY`
   - `MAILJET_API_SECRET`
   - `MAILJET_FROM_EMAIL`
   - `MAILJET_FROM_NAME`
7. Run migrations:
   ```bash
   php artisan migrate
   ```
8. Start the development server:
   ```bash
   php artisan serve
   ```

## Usage

1. Visit the welcome page
2. Register a new account or login
3. Access the dashboard to view recent reports
4. Create new troubleshooting reports
5. Export reports as PDF or send via email

## Technologies Used

- Laravel 10
- DomPDF (PDF generation)
- Mailjet API (Email sending)
- MySQL (Database)

