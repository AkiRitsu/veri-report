# Database Structure Explanation
## Troubleshooting Report Management System

This document explains all the database tables in the Troubleshooting Report Management System, their purpose, structure, and relationships.

---

## Overview

The system uses **MySQL/MariaDB** as the database and consists of **5 main tables**:
1. `users` - User accounts and authentication
2. `reports` - Troubleshooting reports (main business data)
3. `sessions` - Laravel session storage
4. `cache` - Application cache storage
5. `cache_locks` - Cache locking mechanism

---

## 1. USERS Table

### Purpose
Stores user account information for authentication and user preferences.

### Structure

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique user identifier |
| `name` | VARCHAR(255) | NOT NULL | User's full name |
| `email` | VARCHAR(255) | NOT NULL, UNIQUE | User's email address (used for login) |
| `email_verified_at` | TIMESTAMP | NULLABLE | Timestamp when email was verified (currently not used) |
| `password` | VARCHAR(255) | NOT NULL | Bcrypt hashed password |
| `remember_token` | VARCHAR(100) | NULLABLE | Token for "Remember Me" functionality |
| `dark_mode` | TINYINT(1) | DEFAULT 0 | User's dark mode preference (0=false, 1=true) |
| `created_at` | TIMESTAMP | AUTO | Account creation timestamp |
| `updated_at` | TIMESTAMP | AUTO | Last update timestamp |

### Key Features
- **Email uniqueness**: Each email can only be registered once
- **Password hashing**: Passwords are stored using bcrypt (one-way encryption)
- **Remember token**: Allows users to stay logged in across browser sessions
- **Dark mode**: Per-user theme preference that persists across sessions

### Relationships
- **One-to-Many** with `reports`: One user can create many reports
- **One-to-Many** with `sessions`: One user can have multiple active sessions

### Usage
- User registration and login
- Storing user preferences (dark mode)
- Tracking which user created each report

---

## 2. REPORTS Table

### Purpose
Stores all troubleshooting report data - the core business entity of the system.

### Structure

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique report identifier |
| `user_id` | BIGINT | FOREIGN KEY, NOT NULL | References `users.id` (CASCADE DELETE) |
| `client_name` | VARCHAR(255) | NOT NULL | Client's full name |
| `model_name` | VARCHAR(255) | NOT NULL | Device model name |
| `device_serial_id` | VARCHAR(255) | NOT NULL | Device serial number/ID |
| `device_type` | ENUM | NOT NULL | Device type: 'PC', 'Laptop', 'Mobile Phone' |
| `problem_description` | TEXT | NOT NULL | Description of the problem encountered |
| `fix_description` | TEXT | NULLABLE | Description of the fix applied |
| `phone_number` | VARCHAR(20) | NOT NULL | Client's phone number |
| `client_email` | VARCHAR(255) | NOT NULL | Client's email address |
| `status` | ENUM | DEFAULT 'on-going' | Report status: 'on-going', 'complete' |
| `additional_notes` | TEXT | NULLABLE | Any additional notes or comments |
| `pdf_hash` | VARCHAR(255) | NULLABLE | SHA-256 hash for PDF verification |
| `completed_at` | TIMESTAMP | NULLABLE | Timestamp when report was marked complete |
| `created_at` | TIMESTAMP | AUTO | Report creation timestamp |
| `updated_at` | TIMESTAMP | AUTO | Last update timestamp |

### Key Features
- **Foreign key constraint**: `user_id` references `users.id` with CASCADE DELETE
  - If a user is deleted, all their reports are automatically deleted
- **Status tracking**: Reports start as 'on-going' and can be marked 'complete'
- **PDF hash verification**: SHA-256 hash stored for PDF integrity checking
  - Hash is automatically generated when report is marked complete
  - Used to verify PDF hasn't been tampered with
- **Device type restriction**: Only allows 'PC', 'Laptop', or 'Mobile Phone'
- **Timestamps**: Tracks creation, updates, and completion times

### Relationships
- **Many-to-One** with `users`: Many reports belong to one user
  - Foreign Key: `user_id` → `users.id`
  - On Delete: CASCADE

### Business Logic
- Reports are created with status 'on-going' by default
- When status changes to 'complete', `completed_at` is automatically set
- `pdf_hash` is generated automatically when report is marked complete
- `fix_description` can be null for ongoing reports but should be filled when completed
- All timestamps are stored in UTC and converted to GMT+8 for display

### Usage
- Creating new troubleshooting reports
- Tracking report status and completion
- Exporting reports as PDF
- Sending reports via email
- Searching and filtering reports

---

## 3. SESSIONS Table

### Purpose
Stores Laravel session data for user authentication and session management.

### Structure

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | VARCHAR(255) | PRIMARY KEY | Session identifier (session ID) |
| `user_id` | BIGINT | FOREIGN KEY, NULLABLE, INDEXED | References `users.id` (if authenticated) |
| `ip_address` | VARCHAR(45) | NULLABLE | Client IP address (supports IPv6) |
| `user_agent` | TEXT | NULLABLE | Client browser/user agent string |
| `payload` | LONGTEXT | NOT NULL | Serialized session data |
| `last_activity` | INTEGER | INDEXED | Unix timestamp of last activity |

### Key Features
- **Session storage**: Stores all session data (authentication, flash messages, etc.)
- **User tracking**: Links sessions to users when authenticated
- **Activity tracking**: `last_activity` tracks when session was last used
- **IP and User Agent**: Stores client information for security tracking
- **Nullable user_id**: Sessions can exist without authenticated users (guest sessions)

### Relationships
- **Many-to-One** with `users`: Many sessions can belong to one user (nullable)
  - Foreign Key: `user_id` → `users.id`
  - No cascade (sessions are managed separately)

### Usage
- Maintaining user login state
- Storing temporary data (flash messages, form data)
- Session timeout management
- Security tracking (IP, user agent)

---

## 4. CACHE Table

### Purpose
Stores application cache data for performance optimization.

### Structure

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `key` | VARCHAR(255) | PRIMARY KEY | Cache key identifier |
| `value` | MEDIUMTEXT | NOT NULL | Cached data (serialized) |
| `expiration` | INTEGER | NOT NULL | Unix timestamp when cache expires |

### Key Features
- **Key-value storage**: Simple key-value pair storage
- **Expiration**: Automatic cache expiration based on timestamp
- **Performance**: Reduces database queries by caching frequently accessed data

### Usage
- Caching configuration data
- Caching query results
- Storing temporary computed data
- Performance optimization

---

## 5. CACHE_LOCKS Table

### Purpose
Provides distributed locking mechanism for cache operations.

### Structure

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `key` | VARCHAR(255) | PRIMARY KEY | Lock key identifier |
| `owner` | VARCHAR(255) | NOT NULL | Lock owner identifier |
| `expiration` | INTEGER | NOT NULL | Unix timestamp when lock expires |

### Key Features
- **Distributed locking**: Prevents race conditions in multi-server environments
- **Lock ownership**: Tracks which process owns the lock
- **Automatic expiration**: Locks expire automatically to prevent deadlocks

### Usage
- Preventing concurrent cache writes
- Ensuring atomic operations
- Multi-server synchronization

---

## Database Relationships Summary

```
USERS (1) ──────< (N) REPORTS
   │
   └───────< (N) SESSIONS (optional)

CACHE (standalone)
CACHE_LOCKS (standalone)
```

### Primary Relationships

1. **USERS → REPORTS (One-to-Many)**
   - **Cardinality**: 1:N
   - **Type**: Required relationship
   - **Foreign Key**: `reports.user_id` → `users.id`
   - **Cascade**: ON DELETE CASCADE (deleting a user deletes all their reports)
   - **Required**: Yes (every report must have a user)

2. **USERS → SESSIONS (One-to-Many)**
   - **Cardinality**: 1:N (optional)
   - **Type**: Optional relationship
   - **Foreign Key**: `sessions.user_id` → `users.id`
   - **Cascade**: None
   - **Required**: No (sessions can exist without authenticated users)

### Standalone Tables

- **CACHE**: System table for application caching (no foreign keys)
- **CACHE_LOCKS**: System table for distributed locking (no foreign keys)

---

## Data Flow Examples

### 1. User Registration Flow
```
1. User submits registration form
2. New record inserted into `users` table
3. Password is hashed using bcrypt
4. User is automatically logged in
5. Session created in `sessions` table linked to `user_id`
```

### 2. Report Creation Flow
```
1. Authenticated user creates a report
2. New record inserted into `reports` table
3. `user_id` links to authenticated user
4. `status` defaults to 'on-going'
5. `created_at` and `updated_at` automatically set
6. `pdf_hash` is NULL initially
```

### 3. Report Completion Flow
```
1. User marks report as complete
2. `status` updated to 'complete'
3. `completed_at` timestamp set
4. PDF is automatically generated
5. SHA-256 hash calculated from PDF
6. `pdf_hash` stored in database
7. `updated_at` automatically updated
```

### 4. Session Management Flow
```
1. User logs in
2. Session created in `sessions` table
3. `user_id` linked to authenticated user
4. `last_activity` updated on each request
5. Session expires after inactivity
6. Session deleted when user logs out
```

---

## Indexes

### Primary Keys
- `users.id` - Auto-incrementing big integer
- `reports.id` - Auto-incrementing big integer
- `sessions.id` - String (session ID)
- `cache.key` - String (cache key)
- `cache_locks.key` - String (lock key)
- `password_reset_tokens.email` - String (email address)

### Foreign Key Indexes
- `reports.user_id` - Indexed for join performance
- `sessions.user_id` - Indexed for user session lookup

### Additional Indexes
- `sessions.last_activity` - Indexed for session cleanup queries
- `users.email` - Unique index for email lookup

---

## Constraints

### Foreign Key Constraints
- `reports.user_id` → `users.id` (CASCADE DELETE)
- `sessions.user_id` → `users.id` (NO ACTION)

### Unique Constraints
- `users.email` - Must be unique across all users
- `password_reset_tokens.email` - One token per email

### Check Constraints (via ENUM)
- `reports.device_type` - Must be one of: 'PC', 'Laptop', 'Mobile Phone'
- `reports.status` - Must be one of: 'on-going', 'complete'

### Default Values
- `users.dark_mode` - Default: 0 (false)
- `reports.status` - Default: 'on-going'

---

## Data Types Explained

### VARCHAR vs TEXT
- **VARCHAR(255)**: Fixed maximum length, efficient for short strings (names, emails)
- **TEXT**: Variable length, no maximum, used for longer content (descriptions, notes)
- **LONGTEXT**: Very large text fields (session payloads)

### ENUM
- **ENUM**: Restricted to predefined values
- Used for `device_type` and `status` to ensure data integrity
- More efficient than VARCHAR for limited value sets

### TIMESTAMP
- **TIMESTAMP**: Stores date and time
- Automatically managed by Laravel (`created_at`, `updated_at`)
- Stored in UTC, converted to GMT+8 for display

### TINYINT(1)
- **TINYINT(1)**: Boolean-like field (0 or 1)
- Used for `dark_mode` preference
- More efficient than VARCHAR for boolean values

---

## Security Considerations

1. **Password Storage**: Passwords are hashed using bcrypt (one-way encryption)
2. **Session Security**: Session IDs are randomly generated and stored securely
3. **Foreign Key Constraints**: Ensure referential integrity
4. **CASCADE DELETE**: Prevents orphaned records when users are deleted
5. **PDF Hash**: SHA-256 hash ensures PDF integrity and prevents tampering
6. **Email Uniqueness**: Prevents duplicate accounts

---

## Maintenance Notes

1. **Session Cleanup**: Old sessions should be periodically cleaned up based on `last_activity`
2. **Cache Expiration**: Expired cache entries are automatically ignored
3. **Backup Strategy**: Regular backups should include all tables, especially `users` and `reports`

---

## Future Enhancement Possibilities

1. **Email Verification**: Use `email_verified_at` for email verification workflow
2. **Soft Deletes**: Add soft delete functionality to preserve deleted reports
4. **Audit Trail**: Add audit log table to track all changes
5. **File Attachments**: Add table for report file attachments
6. **Report Categories**: Add categories/tags for better organization
7. **Client Management**: Separate `clients` table for better client data management

---

## Summary

The database structure is designed to:
- **Store user accounts** with authentication and preferences
- **Track troubleshooting reports** with complete lifecycle management
- **Manage sessions** for user authentication
- **Optimize performance** through caching
- **Ensure data integrity** through foreign keys and constraints
- **Support security** through password hashing and PDF verification

The core business logic revolves around the `reports` table, which is linked to `users` and contains all the troubleshooting information needed to manage device repair workflows.

