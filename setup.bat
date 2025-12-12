@echo off
echo ========================================
echo Troubleshooting Report System Setup
echo ========================================
echo.

echo [1/6] Creating required directories...
if not exist bootstrap\cache mkdir bootstrap\cache
echo.

echo [2/6] Installing Composer dependencies...
call composer install
if errorlevel 1 (
    echo ERROR: Composer install failed!
    pause
    exit /b 1
)
echo.

echo [3/6] Copying environment file...
if not exist .env (
    copy .env.example .env
    echo .env file created. Please edit it with your database and Mailjet credentials.
) else (
    echo .env file already exists.
)
echo.

echo [4/6] Generating application key...
call php artisan key:generate
if errorlevel 1 (
    echo ERROR: Key generation failed!
    pause
    exit /b 1
)
echo.

echo [5/6] Setting storage permissions...
icacls storage /grant Users:F /T >nul 2>&1
icacls bootstrap\cache /grant Users:F /T >nul 2>&1
echo.

echo [6/6] Setup complete!
echo.
echo IMPORTANT: Before running migrations, please:
echo 1. Edit .env file with your database credentials
echo 2. Create the database in MySQL
echo 3. Run: php artisan migrate
echo.
echo To start the server, run: php artisan serve
echo.
pause

