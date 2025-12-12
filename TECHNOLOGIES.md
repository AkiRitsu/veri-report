# Technologies Used in Troubleshooting Report Management System

## Backend Framework & Core
- **Laravel 10.10+** - PHP web application framework
- **PHP 8.1+** - Server-side programming language
- **Composer** - PHP dependency manager

## Database
- **MySQL/MariaDB** - Relational database management system
- **Laravel Eloquent ORM** - Object-relational mapping for database operations
- **Laravel Migrations** - Database version control and schema management

## Authentication & Security
- **Laravel Sanctum 3.2+** - API token authentication
- **Laravel Authentication** - Built-in user authentication system
- **CSRF Protection** - Cross-site request forgery protection
- **Password Hashing** - Bcrypt password encryption

## PDF Generation
- **DomPDF 2.0+** - PDF generation library for PHP
- **HTML to PDF conversion** - Converts Blade templates to PDF documents

## Email Services
- **Mailjet API v3.1** - Email delivery service
- **Mailjet PHP SDK 1.5+** - Official Mailjet API client library
- **Base64 encoding** - For PDF attachments in emails

## HTTP Client
- **Guzzle HTTP 7.2+** - HTTP client library for making API requests

## Frontend Technologies
- **Blade Templating Engine** - Laravel's server-side templating
- **HTML5** - Markup language
- **CSS3** - Styling with:
  - CSS Variables (Custom Properties)
  - Flexbox layout
  - CSS Grid layout
  - CSS Animations & Transitions
  - Media queries (responsive design)
  - Backdrop filters (glassmorphism effects)
  - CSS Gradients
- **JavaScript (Vanilla)** - Client-side scripting:
  - Intersection Observer API (scroll animations)
  - DOM manipulation
  - Event handling
- **Responsive Web Design** - Mobile-first approach

## Fonts & Typography
- **Figtree Font Family** - Google Fonts via Bunny Fonts CDN
- **Font weights**: 400, 500, 600, 700
- **System fonts fallback**: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto

## Development Tools
- **Laravel Tinker 2.8+** - REPL for Laravel
- **Laravel Pint 1.0+** - Code style fixer
- **Laravel Sail 1.18+** - Docker development environment
- **PHPUnit 10.1+** - PHP testing framework
- **Mockery 1.4.4+** - Mocking framework for testing
- **FakerPHP 1.9.1+** - Fake data generator for testing
- **Spatie Laravel Ignition 2.0+** - Error page handler
- **Nunomaduro Collision 7.0+** - Error handler for CLI

## Server & Deployment
- **Laravel Artisan** - Command-line interface
- **PHP Built-in Server** - Development server (`php artisan serve`)
- **Apache/Nginx** - Production web servers (compatible)

## Data Processing
- **Carbon** - PHP date/time library (via Laravel)
- **Hash Functions** - SHA-256 for PDF verification hashes
- **Base64 Encoding** - For email attachments

## Design Patterns & Architecture
- **MVC (Model-View-Controller)** - Laravel's architectural pattern
- **Service Layer Pattern** - Separate service classes (PdfService, EmailService)
- **Repository Pattern** - Implicit through Eloquent models
- **Dependency Injection** - Laravel's service container

## Configuration & Environment
- **Environment Variables (.env)** - Configuration management
- **Laravel Config System** - Centralized configuration
- **Timezone Configuration** - Asia/Kuala_Lumpur (GMT+8)

## Caching & Sessions
- **Database Sessions** - Session storage in database
- **Laravel Cache** - Caching system (database driver)

## Code Quality
- **PSR-4 Autoloading** - PHP standard for autoloading
- **PSR-12 Coding Standards** - Code style standards (via Laravel Pint)

## Version Control
- **Git** - Version control system (implied by .gitignore)

## Optional/Development Dependencies
- **Node.js & NPM** - For frontend asset compilation (if needed)
- **Docker** - Via Laravel Sail for containerized development

## Third-Party Services & APIs
- **Mailjet Email API** - Transactional email service
- **Bunny Fonts CDN** - Font delivery service

## Browser Technologies Used
- **CSS Custom Properties (CSS Variables)** - For theming
- **CSS Grid & Flexbox** - Modern layout systems
- **CSS Animations** - Keyframe animations
- **CSS Transitions** - Smooth state changes
- **Backdrop Filter** - Glassmorphism effects
- **Intersection Observer API** - Scroll-triggered animations
- **Local Storage** - (Potential for future enhancements)
- **Fetch API** - (Via Laravel's built-in form handling)

## Security Features
- **CSRF Tokens** - Cross-site request forgery protection
- **Password Hashing** - Secure password storage
- **SQL Injection Prevention** - Via Eloquent ORM
- **XSS Protection** - Via Blade's automatic escaping
- **Input Validation** - Laravel form validation
- **File Upload Security** - Secure file handling

## Data Formats
- **JSON** - API responses and configuration
- **PDF** - Report export format
- **HTML** - Web page markup
- **CSS** - Styling
- **JavaScript** - Client-side logic

## UI/UX Features
- **Dark Mode** - User preference-based theme switching
- **Responsive Design** - Mobile, tablet, desktop support
- **Scroll Animations** - Intersection Observer-based animations
- **Glassmorphism** - Modern UI design trend
- **Gradient Backgrounds** - Visual design elements
- **Smooth Transitions** - CSS transition effects

