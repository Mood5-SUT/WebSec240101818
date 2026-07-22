# EduSecurity - Premium Cybersecurity LMS

## Project Documentation

---

## 📋 Table of Contents
1. [Project Overview](#project-overview)
2. [Features](#features)
3. [Technology Stack](#technology-stack)
4. [System Requirements](#system-requirements)
5. [Installation Guide](#installation-guide)
6. [Database Setup](#database-setup)
7. [SSL Certificates Setup](#ssl-certificates-setup)
8. [Social Authentication Setup](#social-authentication-setup)
9. [User Roles & Permissions](#user-roles--permissions)
10. [Default Credentials](#default-credentials)
11. [Application Structure](#application-structure)
12. [API Routes](#api-routes)
13. [Security Features](#security-features)
14. [Troubleshooting](#troubleshooting)
15. [Contributing](#contributing)
16. [License](#license)

---

## Project Overview

**EduSecurity** is a premium Learning Management System (LMS) built specifically for cybersecurity education. It provides a comprehensive platform for students, instructors, and administrators to manage courses, assignments, and submissions in a secure, role-based environment.

The application demonstrates secure coding practices, vulnerability mitigation, and modern web security principles while providing practical hands-on learning experiences.

**Domain**: `www.secure-study.com`  
**Project Type**: Educational/Research  
**Version**: 1.0.0

---

## Features

### 🔐 Authentication & Authorization
- **Email/Password Registration** with validation rules (min 8 chars, mixed case, numbers, symbols)
- **Email Verification** (simulated via logs for development)
- **Social Authentication** (Google, LinkedIn, Facebook, Microsoft)
- **Role-Based Access Control** (RBAC) with Spatie Permission
- **Password Change** functionality
- **Secure Session Management**

### 👥 User Roles
| Role | Permissions |
|------|------------|
| **Course Admin** | Full system access, manage users, create courses, upload content, audit security |
| **Instructor** | Create/edit courses, upload content, grade assignments |
| **Student** | Enroll in courses, submit assignments, request grade reviews |

### 📚 Course Management
- Create, read, update, delete courses
- Upload course materials/syllabus (PDF, slides, documentation)
- Assign instructors to courses
- Keyword search functionality
- Enrollment system

### 📝 Assignments & Submissions
- Create/edit assignments with descriptions
- Upload reference files
- Student submissions with text and file uploads
- Grading system (A, B, C, etc.)
- **Grade Review System**: Students can request reviews; instructors can mark as completed
- Download submission files

### 🛡️ Security Auditor Dashboard
- **SSL Certificate Analysis**: View CA and website certificate details
- **Password Robustness Tester**: Validate passwords against security policies
- **SQL Injection Simulator**: Compare vulnerable vs. parameterized queries
- **XSS Simulator**: Demonstrate Blade escaping vs. raw output

### 🎨 UI/UX Features
- Dark mode theme optimized for coding/security focus
- Responsive Bootstrap 5 design
- Font Awesome icons
- Animations and interactive elements
- Flash messages for user feedback
- Clean, modern card-based layout

---

## Technology Stack

### Backend
| Technology | Version | Purpose |
|------------|---------|---------|
| PHP | 8.2+ | Core language |
| Laravel | 11.x / 12.x | Framework |
| MySQL / MariaDB | 10.4+ | Database |
| Laravel Socialite | Latest | Social authentication |
| Spatie Permission | Latest | RBAC |
| OpenSSL | Latest | SSL certificate generation |

### Frontend
| Technology | Version | Purpose |
|------------|---------|---------|
| Bootstrap | 5.3.2 | UI framework |
| Font Awesome | 6.4.2 | Icons |
| Outfit Font | Google Fonts | Typography |
| Tailwind CSS | 4.x | Utility-first CSS |
| Alpine.js / Vanilla JS | - | Interactivity |

### Development Tools
- Composer (PHP dependency management)
- NPM (Node package management)
- Artisan (Laravel CLI)
- PHPMyAdmin (Database management)

---

## System Requirements

### Minimum Requirements
- **PHP**: 8.2 or higher
- **Composer**: Latest version
- **Node.js**: 18.x or higher
- **MySQL**: 5.7+ or MariaDB 10.4+
- **Web Server**: Apache/Nginx or Laravel Valet/Sail
- **Storage**: 100MB minimum (1GB recommended for file uploads)
- **Memory**: 512MB minimum

### Recommended Extensions
- `php-mysql` - Database connectivity
- `php-mbstring` - Multibyte string support
- `php-xml` - XML parsing
- `php-zip` - Archive handling
- `php-curl` - HTTP requests
- `php-openssl` - SSL/TLS operations
- `php-gd` - Image processing
- `php-intl` - Internationalization
- `php-bcmath` - Mathematical operations

---

## Installation Guide

### Step 1: Clone the Repository
```bash
git clone https://github.com/yourusername/edusecurity.git
cd edusecurity
```

### Step 2: Install PHP Dependencies
```bash
composer install
```

### Step 3: Install Frontend Dependencies
```bash
npm install
```

### Step 4: Environment Configuration
```bash
# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 5: Configure .env File
Update the following variables in your `.env` file:

```env
# Application Configuration
APP_NAME=EduSecurity
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edusecurity
DB_USERNAME=root
DB_PASSWORD=your_password

# Mail Configuration (Use log for development)
MAIL_MAILER=log

# Social Authentication (Optional - Add your keys)
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

LINKEDIN_CLIENT_ID=your_linkedin_client_id
LINKEDIN_CLIENT_SECRET=your_linkedin_client_secret
LINKEDIN_REDIRECT_URI=http://localhost:8000/auth/linkedin/callback

FACEBOOK_CLIENT_ID=your_facebook_client_id
FACEBOOK_CLIENT_SECRET=your_facebook_client_secret
FACEBOOK_REDIRECT_URI=http://localhost:8000/auth/facebook/callback

MICROSOFT_CLIENT_ID=your_microsoft_client_id
MICROSOFT_CLIENT_SECRET=your_microsoft_client_secret
MICROSOFT_REDIRECT_URI=http://localhost:8000/auth/microsoft/callback
```

### Step 6: Build Frontend Assets
```bash
npm run build
```

### Step 7: Create Storage Symbolic Link
```bash
php artisan storage:link
```

This creates a symbolic link from `public/storage` to `storage/app/public` for file uploads.

### Step 8: Run Migrations & Seeders
```bash
# Run migrations
php artisan migrate

# Seed the database with roles, permissions, and demo data
php artisan db:seed
```

### Step 9: Start Development Server
```bash
# Option 1: Laravel's built-in server
php artisan serve

# Option 2: Using Valet (if installed)
valet link edusecurity
```

### Step 10: Access the Application
Open your browser and navigate to:
```
http://localhost:8000
```

---

## Database Setup

### Manual Database Creation
```sql
CREATE DATABASE edusecurity CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Database Schema Overview
The application uses the following tables:

| Table | Description |
|-------|-------------|
| `users` | User accounts with social auth fields |
| `roles` | Spatie permission roles |
| `permissions` | Spatie permission definitions |
| `courses` | Course information |
| `assignments` | Assignment details |
| `submissions` | Student submissions |
| `enrollments` | Course enrollment records |
| `cache` | Application cache |
| `sessions` | User sessions |
| `jobs` | Queue jobs |

### Seeder Data
The seeders create:
1. **Roles**: `course_admin`, `instructor`, `student`
2. **Permissions**: `upload_content`, `enroll_course`, `submit_assignment`, `create_course`, `audit_security`
3. **Default Users**: Admin, Instructor, 2 Students
4. **Sample Courses**: 3 cybersecurity courses
5. **Sample Assignments**: 3 assignments with submissions
6. **Enrollments**: Student enrollments in courses

---

## SSL Certificates Setup

### Certificate Directory Structure
Create a `certificates` directory in the root of your project:
```
edusecurity/
├── certificates/
│   ├── ca.crt              # Root CA Certificate
│   ├── ca.key              # Root CA Private Key
│   ├── ca.srl              # CA Serial Number
│   ├── secure-study.crt    # Website SSL Certificate
│   ├── secure-study.csr    # Certificate Signing Request
│   ├── secure-study.ext    # X.509 Extension Configuration
│   └── secure-study.key    # Website Private Key
```

### Generate SSL Certificates (Development)

#### 1. Generate Root CA Certificate
```bash
# Generate CA private key
openssl genrsa -out certificates/ca.key 2048

# Generate CA certificate (valid for 10 years)
openssl req -x509 -new -nodes -key certificates/ca.key -sha256 -days 3650 -out certificates/ca.crt \
  -subj "/C=US/ST=State/L=City/O=Education Authority/CN=Education Root CA"
```

#### 2. Generate Website Certificate
```bash
# Generate website private key
openssl genrsa -out certificates/secure-study.key 2048

# Create CSR configuration file (secure-study.csr.conf)
cat > certificates/secure-study.csr.conf << EOF
[ req ]
default_bits = 2048
prompt = no
default_md = sha256
distinguished_name = dn

[ dn ]
C = US
ST = State
L = City
O = EduSecurity
OU = Education Department
CN = www.secure-study.com
EOF

# Generate CSR
openssl req -new -key certificates/secure-study.key -out certificates/secure-study.csr -config certificates/secure-study.csr.conf

# Create extension file (secure-study.ext)
cat > certificates/secure-study.ext << EOF
authorityKeyIdentifier=keyid,issuer
basicConstraints=CA:FALSE
keyUsage = digitalSignature, nonRepudiation, keyEncipherment, dataEncipherment
subjectAltName = @alt_names

[alt_names]
DNS.1 = secure-study.com
DNS.2 = www.secure-study.com
EOF

# Sign the certificate with CA
openssl x509 -req -in certificates/secure-study.csr -CA certificates/ca.crt -CAkey certificates/ca.key \
  -CAcreateserial -out certificates/secure-study.crt -days 365 -sha256 -extfile certificates/secure-study.ext
```

### Configure Web Server for HTTPS

#### Apache Configuration
```apache
<VirtualHost *:443>
    ServerName www.secure-study.com
    DocumentRoot /path/to/edusecurity/public

    SSLEngine on
    SSLCertificateFile /path/to/edusecurity/certificates/secure-study.crt
    SSLCertificateKeyFile /path/to/edusecurity/certificates/secure-study.key
    SSLCertificateChainFile /path/to/edusecurity/certificates/ca.crt
</VirtualHost>
```

#### Nginx Configuration
```nginx
server {
    listen 443 ssl http2;
    server_name www.secure-study.com;

    ssl_certificate /path/to/edusecurity/certificates/secure-study.crt;
    ssl_certificate_key /path/to/edusecurity/certificates/secure-study.key;
    ssl_trusted_certificate /path/to/edusecurity/certificates/ca.crt;

    root /path/to/edusecurity/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### Validate Certificate Installation
```bash
# Test SSL configuration
openssl s_client -connect localhost:443 -CAfile certificates/ca.crt

# Check certificate details
openssl x509 -in certificates/secure-study.crt -text -noout
```

---

## Social Authentication Setup

### Google OAuth Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing
3. Navigate to **APIs & Services > Credentials**
4. Click **Create Credentials > OAuth client ID**
5. Select **Web application**
6. Add authorized redirect URI:
   ```
   http://localhost:8000/auth/google/callback
   ```
7. Copy Client ID and Secret to `.env`

### LinkedIn OAuth Setup

1. Go to [LinkedIn Developer Portal](https://www.linkedin.com/developers/)
2. Create a new app
3. Navigate to **Products** and add **Sign In with LinkedIn**
4. Add OAuth 2.0 redirect URL:
   ```
   http://localhost:8000/auth/linkedin/callback
   ```
5. Copy Client ID and Secret to `.env`

### Facebook OAuth Setup

1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Create a new app
3. Configure **Facebook Login** product
4. Add valid OAuth redirect URI:
   ```
   http://localhost:8000/auth/facebook/callback
   ```
5. Copy App ID and Secret to `.env`

### Microsoft OAuth Setup

1. Go to [Azure Portal](https://portal.azure.com/)
2. Navigate to **Azure Active Directory > App registrations**
3. Create a new registration
4. Add redirect URI (Web platform):
   ```
   http://localhost:8000/auth/microsoft/callback
   ```
5. Copy Application (client) ID and Secret to `.env`

---

## User Roles & Permissions

### Role Definitions

#### Course Admin
- Full system access
- Can assign roles to users
- Can create/delete any course
- Can view all submissions
- Can audit security

#### Instructor
- Create and edit courses
- Upload course materials
- Grade submissions
- Manage assignments for their courses

#### Student
- Enroll in available courses
- Submit assignments
- Request grade reviews
- View own grades

### Permission Matrix

| Permission | Course Admin | Instructor | Student |
|------------|--------------|------------|---------|
| `upload_content` | ✅ | ✅ | ❌ |
| `enroll_course` | ✅ | ❌ | ✅ |
| `submit_assignment` | ✅ | ❌ | ✅ |
| `create_course` | ✅ | ❌ | ❌ |
| `audit_security` | ✅ | ❌ | ❌ |

### User Role Management
Administrators can manage user roles through the **Manage Roles** panel:
- Access via navigation menu (`/admin/users`)
- Select a user and assign a role
- Roles are synchronized immediately

---

## Default Credentials

### Administrator
- **Email**: `admin@study.com`
- **Password**: `Secret123!`
- **Role**: Course Admin

### Instructor
- **Email**: `instructor@study.com`
- **Password**: `Secret123!`
- **Role**: Instructor

### Students
- **Student 1**: `student@study.com` / `Secret123!`
- **Student 2**: `student2@study.com` / `Secret123!`

### Password Policy
All default accounts use `Secret123!` which meets the security requirements:
- ✅ Minimum 8 characters
- ✅ Contains uppercase and lowercase letters
- ✅ Contains numbers
- ✅ Contains symbols

---

## Application Structure

```
edusecurity/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Web/
│   │   │   │   ├── AssignmentController.php
│   │   │   │   ├── CourseController.php
│   │   │   │   ├── SecurityController.php
│   │   │   │   └── UsersController.php
│   │   │   └── Controller.php
│   │   └── Middleware/
│   └── Models/
│       ├── Assignment.php
│       ├── Course.php
│       ├── Enrollment.php
│       ├── Submission.php
│       └── User.php
├── bootstrap/
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── cache.php
│   ├── database.php
│   ├── filesystems.php
│   ├── logging.php
│   ├── mail.php
│   ├── permission.php
│   ├── queue.php
│   ├── services.php
│   └── session.php
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   └── storage/ (symlink to storage/app/public)
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── auth/
│       ├── courses/
│       ├── assignments/
│       ├── submissions/
│       ├── security/
│       ├── layouts/
│       ├── home.blade.php
│       └── welcome.blade.php
├── storage/
│   ├── app/
│   │   └── public/
│   │       ├── assignments/
│   │       ├── materials/
│   │       └── submissions/
│   └── logs/
├── certificates/
│   ├── ca.crt
│   ├── ca.key
│   ├── secure-study.crt
│   ├── secure-study.key
│   └── secure-study.ext
├── .env
├── artisan
├── composer.json
└── package.json
```

---

## API Routes

### Authentication Routes
| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/login` | `login` | Login page |
| POST | `/do_login` | `do_login` | Login handler |
| GET | `/register` | `register` | Registration page |
| POST | `/do_register` | `do_register` | Registration handler |
| POST | `/do_logout` | `do_logout` | Logout handler |
| GET | `/verify/{token?}` | `verify` | Email verification |
| POST | `/change-password` | `change_password` | Change password |

### Social Authentication
| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/auth/{provider}` | `social_login` | Redirect to provider |
| GET | `/auth/{provider}/callback` | `social_callback` | OAuth callback |

### Course Routes
| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/` | `home` | Home page |
| GET | `/courses` | `courses_list` | Course list |
| GET | `/courses/show/{id}` | `courses_show` | Course details |
| GET | `/courses/edit/{id?}` | `courses_edit` | Create/edit course |
| POST | `/courses/save` | `courses_save` | Save course |
| POST | `/courses/delete/{id}` | `courses_delete` | Delete course |
| GET | `/courses/materials/{course_id}` | `courses_edit_materials` | Upload materials |
| POST | `/courses/save-materials` | `courses_save_materials` | Save materials |
| POST | `/courses/delete-materials/{id}` | `courses_delete_materials` | Delete materials |
| POST | `/courses/enroll` | `courses_enroll` | Enroll in course |

### Assignment Routes
| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/assignments/edit/{course_id}/{id?}` | `assignments_edit` | Create/edit assignment |
| POST | `/assignments/save` | `assignments_save` | Save assignment |

### Submission Routes
| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/submissions` | `submissions_list` | Submission list |
| POST | `/submissions/submit` | `submissions_submit` | Submit assignment |
| POST | `/submissions/grade` | `submissions_grade` | Grade submission |
| POST | `/submissions/request-review` | `submissions_request_review` | Request grade review |

### User Management Routes
| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/admin/users` | `users_list` | List users |
| POST | `/admin/users/save` | `users_save` | Update user roles |

### Security Routes
| Method | URI | Name | Description |
|--------|-----|------|-------------|
| GET | `/security-check` | `security_check` | Security dashboard |
| GET | `/security-check/test-password` | `security_test_password` | Password test |
| GET | `/security-check/test-sqli` | `security_test_sqli` | SQL injection test |
| GET | `/security-check/test-xss` | `security_test_xss` | XSS test |

---

## Security Features

### Authentication Security
- **Password Hashing**: Uses Laravel's `bcrypt` hashing (12 rounds)
- **CSRF Protection**: All forms include CSRF tokens
- **Session Security**: HTTP-only cookies with configurable lifetime
- **Email Verification**: Required before login
- **Password Rules**: Minimum 8 chars, mixed case, numbers, symbols
- **Rate Limiting**: Built-in Laravel rate limiting

### Application Security
- **Role-Based Access**: Fine-grained permissions with Spatie
- **Input Validation**: Laravel validation rules on all inputs
- **File Upload Security**: Max file size (10MB), validation, storage outside webroot
- **SQL Injection Prevention**: Eloquent ORM with parameterized queries
- **XSS Prevention**: Blade's automatic escaping (`{{ }}`)
- **Secure File Storage**: Files stored in `storage/app/public` with symbolic link

### SSL/TLS Security
- **Certificate Authority**: Custom CA for internal testing
- **Domain Certificate**: 1-year validity with SAN support
- **Secure Cookies**: Cookie security flags configurable

### Security Auditing
- **Password Robustness**: Validate passwords against policies
- **SQL Injection Simulator**: Compare vulnerable vs. secure code
- **XSS Simulator**: Demonstrate escaping mechanisms
- **SSL Certificate Analysis**: View certificate details and validity

---

## Troubleshooting

### Common Issues and Solutions

#### 1. Storage Link Not Working
```bash
# Remove existing link if present
rm -rf public/storage

# Create new link
php artisan storage:link
```

#### 2. Permission Issues
```bash
# Fix file permissions (Linux/Mac)
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage/logs storage/app/public

# Set ownership
sudo chown -R $USER:www-data storage bootstrap/cache
```

#### 3. Database Connection Issues
```bash
# Check database credentials in .env
# Test connection
php artisan db:show

# Clear config cache
php artisan config:clear
```

#### 4. Migration Errors
```bash
# Reset migrations (WARNING: This will delete all data)
php artisan migrate:fresh --seed

# Or run specific migrations
php artisan migrate --path=/database/migrations/2026_06_10_000001_create_courses_table.php
```

#### 5. Social Authentication Errors
- Verify redirect URIs match exactly
- Check environment variables in `.env`
- Clear config cache: `php artisan config:clear`
- Check SSL/TLS configuration for providers

#### 6. SSL Certificate Errors
```bash
# Verify certificate exists
ls -la certificates/

# Check certificate validity
openssl x509 -in certificates/secure-study.crt -noout -dates

# Re-generate certificates
# See SSL Certificates Setup section above
```

#### 7. File Upload Issues
```bash
# Check storage link
ls -la public/storage

# Verify directory permissions
ls -la storage/app/public/

# Check PHP file upload limits in php.ini
php -i | grep upload_max_filesize
```

#### 8. Cache Issues
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Optimize for production
php artisan optimize
```

### Error Logs
```bash
# Laravel log
tail -f storage/logs/laravel.log

# PHP error log (location varies by OS)
# Linux: /var/log/php_errors.log
# Windows: C:\php\logs\php_errors.log
```

### Debug Mode
Set `APP_DEBUG=true` in `.env` for development to see detailed error messages. Disable in production.

---

## Contributing

### Development Workflow

1. **Fork the repository**
2. **Create a feature branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```
3. **Make your changes**
4. **Run tests** (if applicable)
5. **Commit your changes**
   ```bash
   git commit -m "Description of changes"
   ```
6. **Push to your fork**
   ```bash
   git push origin feature/your-feature-name
   ```
7. **Create a Pull Request**

### Coding Standards
- Follow PSR-12 coding standards
- Use meaningful variable and function names
- Document complex logic with comments
- Write clean, maintainable code

### Security Considerations
- Never commit sensitive information
- Use environment variables for configuration
- Validate and sanitize all inputs
- Use prepared statements for database queries
- Implement proper authentication and authorization

---

## License

This project is part of the **Web & Security Technologies** course. All rights reserved.

### Academic Use
- This project is intended for educational and research purposes
- Use in academic environments is permitted
- Proper attribution is required

### Commercial Use
- Commercial use requires explicit permission
- Contact the course administrator for licensing

### Disclaimer
This application is designed for security education and testing purposes only. Users are responsible for:
- Ensuring they have permission to test security features
- Using the application in a controlled environment
- Not using vulnerabilities for malicious purposes

---

## Support

### Resources
- **Course Website**: `www.secure-study.com`
- **Documentation**: See README.md and project files
- **Issue Tracker**: [GitHub Issues](https://github.com/yourusername/edusecurity/issues)

### Contact
- **Course Administrator**: admin@secure-study.com
- **Technical Support**: tech@secure-study.com

---

## Acknowledgments

- Laravel Framework
- Spatie Permission
- Laravel Socialite
- Bootstrap 5
- Font Awesome
- OpenSSL

---

## Changelog

### Version 1.0.0 (2026-06-10)
- Initial release
- Complete LMS functionality
- Role-based access control
- Social authentication integration
- Security auditor dashboard
- Grade review system
- SSL certificate management
