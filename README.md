# Library Management System

A secure role-based `Library Management System` built with `Laravel 12.56.0`, `PHP 8.2.12`, `MySQL`, `Bootstrap`, and `spatie/laravel-permission`.

This project was developed as a course project based on the required secure WebSecTest-style architecture. It includes custom authentication, role-based authorization, book management, and a transactional borrowing system.

## Project Stack

- Laravel `12.56.0`
- PHP `8.2.12`
- MySQL
- Bootstrap
- Spatie `laravel-permission`

## Main Features

- User registration and login
- Automatic `Member` role assignment after registration
- Custom authentication using `UsersController` and `Auth::attempt()`
- Admin creation of Librarian accounts
- Admin view of roles and permissions
- Admin and Librarian view of registered members
- Admin and Librarian management of books
- Member access to the library catalogue
- Member-only borrowing system
- Profile page with borrowing information for Members
- Admin ability to update Member passwords
- Login rate limiting after 3 failed attempts for 15 seconds

## Roles And Permissions

### Roles

- `Admin`
- `Librarian`
- `Member`

### Permissions

- `view_roles`
- `view_members`
- `manage_users`
- `manage_books`

### Access Summary

- Admin:
  - view roles and permissions
  - create librarians
  - update member passwords
  - view members
  - manage books

- Librarian:
  - view members
  - manage books

- Member:
  - view catalogue
  - borrow books
  - view own borrowed books and borrowing status

## Database

The project uses the following local MySQL settings in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Library_db
DB_USERNAME=root
DB_PASSWORD=
```

## Seeded Accounts

After running migrations and seeders, these accounts are available:

- Admin:
  - Email: `admin@library.com`
  - Password: `password`

- Member:
  - Email: `member@library.com`
  - Password: `password`

## Installation

### 1. Clone Or Open The Project

Place the project in your local workspace.

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment

Make sure your `.env` file contains the correct MySQL database settings:

```env
DB_DATABASE=Library_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate App Key

```bash
php artisan key:generate
```

### 5. Run Migrations And Seeders

```bash
php artisan migrate:fresh --seed
```

### 6. Start The Development Server

```bash
php artisan serve
```

Then open:

```text
http://127.0.0.1:8000
```

## Important System Behavior

### Registration

- A new user registers from the register page
- Password is hashed using `bcrypt($request->password)`
- The system automatically assigns the `Member` role

### Login

- Login uses:

```php
Auth::attempt(['email' => $email, 'password' => $password])
```

- If login fails 3 times, the user is blocked for 15 seconds

### Book Management

Admin and Librarian can:

- add books
- edit books
- delete books

Each book contains:

- Title
- Author
- ISBN
- Copies

### Borrowing Flow

- Only logged-in Members can borrow books
- Borrowing succeeds only if `Copies > 0`
- If copies are unavailable, the system shows:

```text
Book Currently Unavailable
```

- After a successful borrow:
  - a borrow record is created
  - the available copies count is reduced by 1

### Member Profile

Members can see:

- borrowing limit
- remaining borrowing slots
- borrowing status
- currently borrowed books

Admin and Librarian do not see borrowing details on their profile page.

## Security Features

- Input validation using `$request->validate(...)`
- Password hashing using `bcrypt()`
- Old password verification using `Hash::check()`
- Role and permission control using Spatie
- Controller-level authorization checks with `abort(403)`
- Login rate limiting after repeated failures
- Transactional borrowing process to prevent stock inconsistency
- No raw SQL queries

## Project Structure

### Controllers

- `app/Http/Controllers/Web/UsersController.php`
- `app/Http/Controllers/Web/BooksController.php`
- `app/Http/Controllers/Web/BorrowsController.php`
- `app/Http/Controllers/Web/RolesController.php`
- `app/Http/Controllers/Web/HomeController.php`

### Models

- `app/Models/User.php`
- `app/Models/Book.php`
- `app/Models/Borrow.php`

### Routes

- `routes/web.php`

### Views

- `resources/views/layouts`
- `resources/views/home`
- `resources/views/auth`
- `resources/views/users`
- `resources/views/books`
- `resources/views/roles`

### Seeders

- `database/seeders/DatabaseSeeder.php`
- `database/seeders/RolePermissionSeeder.php`

## Testing

Run the automated tests with:

```bash
php artisan test
```

Current tested scenarios include:

- registration assigns the Member role
- member cannot borrow an out-of-stock book
- admin can update member passwords
- login is blocked after 3 failed attempts for 15 seconds

## Discussion Summary

This project demonstrates:

- Laravel MVC structure
- custom authentication
- role-based access control
- secure password handling
- transactional borrowing logic
- protection against unauthorized access and brute-force login attempts

## Notes

- `APP_DEBUG=true` is currently enabled for local development
- In production, `APP_DEBUG` should be set to `false`

