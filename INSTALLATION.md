# Installation Guide

## Prerequisites

Before installing this project, ensure you have the following installed on your Windows 11 system:

1. **PHP 8.1 or higher** - Download from [php.net](https://www.php.net/downloads.php)
2. **Composer** - Download from [getcomposer.org](https://getcomposer.org/download/)
3. **MySQL** - Download from [mysql.com](https://dev.mysql.com/downloads/installer/)
4. **Node.js and NPM** (optional, for frontend assets) - Download from [nodejs.org](https://nodejs.org/)

## Installation Steps

### 1. Create Required Directories

Create the `bootstrap/cache` directory (required for Laravel):

```bash
mkdir bootstrap\cache
```

Or use PowerShell:
```powershell
New-Item -ItemType Directory -Force -Path bootstrap\cache
```

### 2. Install Dependencies

Open PowerShell or Command Prompt in the project directory and run:

```bash
composer install
```

### 3. Environment Configuration

1. Copy the `.env.example` file to `.env`:
   ```bash
   copy .env.example .env
   ```

2. Generate application key:
   ```bash
   php artisan key:generate
   ```

3. Edit the `.env` file and configure:
   - Database connection:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=troubleshooting_reports
     DB_USERNAME=root
     DB_PASSWORD=your_password
     ```
   
   - Mailjet API credentials:
     ```
     MAILJET_API_KEY=your_mailjet_api_key
     MAILJET_API_SECRET=your_mailjet_api_secret
     MAILJET_FROM_EMAIL=noreply@yourdomain.com
     MAILJET_FROM_NAME="Troubleshooting Report System"
     ```

### 4. Database Setup

1. Create a MySQL database named `troubleshooting_reports` (or your preferred name)

2. Run migrations:
   ```bash
   php artisan migrate
   ```

### 5. Storage Permissions

Ensure the storage directory is writable:
```bash
icacls storage /grant Users:F /T
icacls bootstrap\cache /grant Users:F /T
```

### 6. Start the Development Server

Run the Laravel development server:
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Getting Mailjet API Credentials

1. Sign up for a free account at [Mailjet](https://www.mailjet.com/)
2. Go to Account Settings > API Keys
3. Copy your API Key and Secret Key
4. Add them to your `.env` file

## First Use

1. Visit `http://localhost:8000` in your browser
2. Click "Register" to create your first account
3. After registration, you'll be automatically logged in
4. Start creating troubleshooting reports!

## Troubleshooting

### Database Connection Error

**Error: "No connection could be made because the target machine actively refused it"**

This means MySQL is not running or not accessible. Follow these steps:

1. **Check if MySQL is installed and running:**
   ```powershell
   Get-Service -Name "*mysql*"
   ```
   Or run the helper script:
   ```bash
   check-mysql.bat
   ```

2. **Start MySQL service:**
   ```bash
   net start MySQL80
   ```
   (Replace `MySQL80` with your actual MySQL service name - could be `MySQL`, `MySQL57`, etc.)

3. **Verify MySQL connection:**
   - Check your `.env` file has correct settings:
     ```
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=troubleshooting_reports
     DB_USERNAME=root
     DB_PASSWORD=your_password
     ```

4. **Create the database if it doesn't exist:**
   ```bash
   mysql -u root -p -e "CREATE DATABASE troubleshooting_reports;"
   ```

5. **Temporary workaround (file-based sessions):**
   If you need to test the app without MySQL, you can temporarily change in `.env`:
   ```
   SESSION_DRIVER=file
   ```
   Note: The app will still need MySQL for user accounts and reports data.

### Permission Errors
- Run the storage permissions command above
- Ensure PHP has write access to storage and bootstrap/cache directories

### Mailjet Email Not Sending
- Verify API credentials in `.env`
- Check Mailjet account status
- Review application logs in `storage/logs/laravel.log`

