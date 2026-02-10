# Laravel Newsfeed Boilerplate

A clean, production-ready Laravel newsfeed application boilerplate with user authentication, posts, reactions, comments, shares, and a comprehensive admin panel.

## 🚀 Quick Start

```bash
# Clone the repository
git clone https://github.com/ubaidkodekaizen/laravel-news-feed.git
cd laravel-news-feed

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database in .env file
# Then run migrations and seeders
php artisan migrate --seed

# Build assets
npm run build

# Start server
php artisan serve
```

Visit `http://localhost:8000` and login with:
- **Admin**: `admin@newsfeed.com` / `12345678`
- **User**: `user@newsfeed.com` / `12345678`

## ✨ Features

- **User Authentication**
  - Registration and login
  - Email verification
  - Password reset
  - Role-based access control (Admin, Manager, Editor, Member)

- **Newsfeed Functionality**
  - Create, edit, and delete posts
  - Upload media (images/videos) to posts
  - React to posts with emoji reactions
  - Comment on posts and reply to comments
  - Share posts
  - Post visibility settings (public/private)
  - User profiles with bio, location, website

- **Admin Panel**
  - User management (view, create, edit, delete, restore)
  - Feed management (view, delete, restore posts and comments)
  - Dashboard with statistics

- **Notifications**
  - In-app notifications for reactions, comments, shares, and replies
  - Notification management (mark as read, delete)

- **Reporting System**
  - Report users and posts
  - Admin moderation tools

## 📋 Requirements

Before you begin, ensure you have the following installed:

- **PHP** >= 8.2 with extensions:
  - BCMath
  - Ctype
  - cURL
  - DOM
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PCRE
  - PDO
  - Tokenizer
  - XML
- **Composer** (PHP package manager)
- **Node.js** >= 18.x and **NPM**
- **MySQL** 5.7+ or **MariaDB** 10.3+
- **Git**

> **Note**: AWS S3 is optional. You can configure it later for media storage, or use local storage by modifying the filesystem configuration.

## 📦 Installation

### Step 1: Clone the Repository

```bash
git clone https://github.com/ubaidkodekaizen/laravel-news-feed.git
cd laravel-news-feed
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

If you encounter any issues, try:
```bash
composer install --no-interaction --prefer-dist
```

### Step 3: Install NPM Dependencies

```bash
npm install
```

### Step 4: Environment Configuration

Copy the example environment file:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

### Step 5: Configure Database

Open the `.env` file and update the database configuration:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=news_feed
DB_USERNAME=root
DB_PASSWORD=your_password
```

**Important**: Create the database first:

```sql
CREATE DATABASE news_feed CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Or using MySQL command line:

```bash
mysql -u root -p -e "CREATE DATABASE news_feed CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### Step 6: Configure AWS S3 (Optional)

If you want to use AWS S3 for media storage, add these to your `.env` file:

```env
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your_bucket_name
AWS_USE_PATH_STYLE_ENDPOINT=false
```

**Note**: If you don't configure S3, the application will use local storage. You may need to create a symbolic link:

```bash
php artisan storage:link
```

### Step 7: Run Migrations

Create all database tables:

```bash
php artisan migrate
```

### Step 8: Seed Database (Recommended)

This will create default roles, permissions, and test users:

```bash
php artisan db:seed
```

Or run migrations and seeders together:

```bash
php artisan migrate --seed
```

This creates:
- ✅ Default roles (Admin, Manager, Editor, Member)
- ✅ Admin user: `admin@newsfeed.com` / `12345678`
- ✅ Regular users: `user@newsfeed.com`, `user1@newsfeed.com`, etc. / `12345678`

### Step 9: Build Frontend Assets

For production:

```bash
npm run build
```

For development with hot-reloading:

```bash
npm run dev
```

### Step 10: Start Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## 🔐 Default Login Credentials

After running the seeders, you can login with:

| Role | Email | Password |
|------|-------|----------|
| **Admin** | `admin@newsfeed.com` | `12345678` |
| **User** | `user@newsfeed.com` | `12345678` |
| **User 1** | `user1@newsfeed.com` | `12345678` |
| **User 2** | `user2@newsfeed.com` | `12345678` |
| **User 3** | `user3@newsfeed.com` | `12345678` |
| **User 4** | `user4@newsfeed.com` | `12345678` |
| **User 5** | `user5@newsfeed.com` | `12345678` |

**Admin Panel**: `/admin/login`

**User Login**: `/login`

**Newsfeed**: `/news-feed` (requires login)

## 🗂️ Project Structure

```
laravel-news-feed/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin panel controllers
│   │   │   ├── API/             # API controllers
│   │   │   ├── Auth/            # Authentication controllers
│   │   │   └── User/            # User-facing controllers
│   │   └── Middleware/          # Custom middleware
│   ├── Models/
│   │   ├── Feed/                # Post, Comment, Reaction, Share models
│   │   ├── Notifications/       # Notification model
│   │   ├── Reports/             # Report model
│   │   ├── System/              # Role, Permission models
│   │   └── Users/                # User model
│   ├── Services/                # Business logic services
│   └── Helpers/                 # Helper functions
├── database/
│   ├── migrations/              # Database migrations
│   └── seeders/                 # Database seeders
├── resources/
│   ├── views/
│   │   ├── admin/               # Admin panel views
│   │   ├── auth/                # Authentication views
│   │   ├── pages/               # Public pages
│   │   └── user/                # User-facing views
│   └── js/                      # JavaScript/React components
├── routes/
│   ├── web.php                  # Web routes
│   └── api.php                  # API routes
└── public/                      # Public assets
```

## 🔌 API Documentation

The application provides RESTful API endpoints for:

- **Authentication**: `/api/register`, `/api/login`, `/api/logout`
- **User Management**: `/api/user/profile/{slug}`, `/api/user/update/personal`
- **Feed Operations**: 
  - Posts: `/api/feed/posts` (GET, POST, PUT, DELETE)
  - Reactions: `/api/feed/reactions` (POST, DELETE)
  - Comments: `/api/feed/posts/{id}/comments` (GET, POST, PUT, DELETE)
  - Shares: `/api/feed/posts/{id}/share` (POST)
- **Notifications**: `/api/notifications` (GET, POST, DELETE)
- **Reports**: `/api/report/user`, `/api/report/post`

All protected API endpoints require Sanctum authentication. Include the token in the `Authorization` header:

```
Authorization: Bearer {your-token}
```

## 👥 Roles and Permissions

The application uses a role-based access control (RBAC) system:

| Role | ID | Description |
|------|----|-------------|
| **Admin** | 1 | Full access to all features and settings |
| **Manager** | 2 | Can manage users and content |
| **Editor** | 3 | Can edit and moderate content |
| **Member** | 4 | Regular user with newsfeed access |

## 🛠️ Development

### Running in Development Mode

For development with hot-reloading and all services:

```bash
composer dev
```

This command starts:
- Laravel development server (port 8000)
- Queue worker
- Log viewer (Pail)
- Vite dev server (port 5173)

### Common Artisan Commands

```bash
# Clear all caches
php artisan optimize:clear

# Clear specific caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Fresh migration (drops all tables and re-runs migrations)
php artisan migrate:fresh --seed

# Create storage link (for local file storage)
php artisan storage:link
```

## 🐛 Troubleshooting

### Issue: "Class not found" or autoload errors

**Solution**: Regenerate autoload files
```bash
composer dump-autoload
```

### Issue: "SQLSTATE[HY000] [1045] Access denied"

**Solution**: Check your database credentials in `.env` file and ensure the database exists.

### Issue: "The stream or file could not be opened"

**Solution**: Set proper permissions on storage and cache directories:
```bash
chmod -R 775 storage bootstrap/cache
```

On Windows, ensure the directories are writable.

### Issue: "Vite manifest not found"

**Solution**: Build the assets:
```bash
npm run build
```

Or for development:
```bash
npm run dev
```

### Issue: "Route [login] not defined"

**Solution**: Clear route cache:
```bash
php artisan route:clear
php artisan config:clear
```

### Issue: Images not displaying

**Solution**: 
1. If using local storage, create symbolic link:
   ```bash
   php artisan storage:link
   ```
2. If using S3, verify your AWS credentials in `.env`

### Issue: "No application encryption key has been specified"

**Solution**: Generate application key:
```bash
php artisan key:generate
```

## 🧪 Testing

Run the test suite:

```bash
php artisan test
```

Or using PHPUnit directly:

```bash
./vendor/bin/phpunit
```

## 📚 Technologies Used

- **Backend**: Laravel 11
- **Frontend**: Blade templates, React 18, Vite
- **Database**: MySQL/MariaDB
- **Storage**: AWS S3 (optional, can use local storage)
- **Authentication**: Laravel Sanctum
- **Queue**: Redis (optional, for background jobs)

## 🔒 Security

- All passwords are hashed using bcrypt
- CSRF protection enabled
- SQL injection protection via Eloquent ORM
- XSS protection via Blade templating
- Rate limiting on authentication endpoints
- Role-based access control

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📞 Support

For support, email support@example.com or open an issue in the [GitHub repository](https://github.com/ubaidkodekaizen/laravel-news-feed/issues).

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com)
- UI components and styling
- Community contributors

---

**Made with ❤️ for developers**
