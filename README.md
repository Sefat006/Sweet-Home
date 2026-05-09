# Sweet Home - Real Estate Management System

Sweet Home is a modern Real Estate Management System built with Laravel. It provides a robust administrative interface for managing properties, admins, and platform users.

## 🚀 Features

### Administrative Dashboard
- **Role-Based Access Control**: Separate interfaces and permissions for Super Admins and Admins.
- **Admin Management**: 
    - Create, view, and delete administrators.
    - Status management (Approved, Pending, Rejected) for admin accounts.
- **Statistics**: Overview of platform activity, including total admins and account status counts.

### User Authentication
- Secure registration and login system.
- Custom authentication flows handled via `AuthController`.
- Guest and Auth middleware protection on routes.

### UI & UX
- Responsive admin panel using Blade templates.
- Modern layout with breadcrumbs and interactive tables.
- Integration with FontAwesome for intuitive iconography.

## 🛠️ Technology Stack

- **Backend**: [Laravel 12](https://laravel.com/)
- **Language**: [PHP 8.2+](https://www.php.net/)
- **Frontend**: Blade Templates, Vanilla CSS, Vite
- **Database**: MySQL / MariaDB
- **Tools**: Composer, NPM

## 📋 Prerequisites

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL

## 🔧 Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/Sefat006/Sweet-Home.git
   cd sweet-home
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install NPM dependencies:**
   ```bash
   npm install
   ```

4. **Environment Setup:**
   ```bash
   copy .env.example .env
   ```
   *Configure your database settings in the `.env` file.*

5. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

6. **Run Migrations:**
   ```bash
   php artisan migrate
   ```

7. **Link Storage (if needed):**
   ```bash
   php artisan storage:link
   ```

## 🚀 Running the Project

1. **Start the development server:**
   ```bash
   php artisan serve
   ```

2. **Run Vite development server:**
   ```bash
   npm run dev
   ```

The application will be accessible at `http://127.0.0.1:8000`.

## 📁 Project Structure Highlights

- `app/Http/Controllers/Admin`: Contains administrative logic (SuperAdmin, Auth).
- `app/Models`: Database models (`User`, `Manager`).
- `resources/views/admin`: Blade templates for the admin dashboard.
- `routes/web.php`: Primary route definitions for the application.
- `app/Helpers`: Custom global helper functions.

---
Developed by [Sefat](https://github.com/Sefat006)
