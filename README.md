This system provides user management functionality with role-based access control (admin and user roles), CRUD operations for user details, and email functionality with logging.

Features
User Roles
Admin: Has full access to all features including:

CRUD operations for user details

Email sending functionality

Viewing email logs

User: Has limited access (view-only for most features)

Pre-configured Users
The system comes with two default users:

Admin User

Email: admin@example.com

Password: password

Role: admin (full privileges)

Email System

Email: user@example.com

Password: password

Role: user (limited privileges)

Functionality
User CRUD Operations
Create, Read, Update, and Delete user records

Access restricted to admin users only

Email System
Send emails to users

Email logging with basic table display

Access restricted to admin users only

Installation
Run migrations to set up database tables:
php artisan migrate

Seed the default users:
php artisan db:seed
