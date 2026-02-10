# Laravel Newsfeed Boilerplate

A clean, production-ready Laravel newsfeed application boilerplate with user authentication, posts, reactions, comments, shares, and a lightweight admin panel.  
Designed as a **teaching boilerplate** so students can quickly clone, run, and extend a basic social newsfeed.

## 🚀 Quick Start (TL;DR)

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

# Configure ONLY your database in .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

# Run migrations + seeders (fresh)
php artisan migrate:fresh --seed

# Build assets
npm run build

# Start server
php artisan serve
```

Visit `http://localhost:8000` and login with:
- **Admin**: `admin@newsfeed.com` / `12345678`
- **User**: `user@newsfeed.com` / `12345678`

## ✨ Features

### User Authentication
- Registration and login
- Email verification
- Password reset
- Role-based access control (Admin, Manager, Editor, Member)
- User profile management (first name, last name, email, phone, bio, photo, location, website)

### Newsfeed Functionality
- **Posts**
  - Create, edit, and delete posts
  - Upload images to posts (via S3 or local storage)
  - Post visibility settings (public/private)
  - Post sharing/reposting
  - Infinite scroll feed with sorting (latest, popular, oldest)
  
- **Reactions**
  - 6 reaction types: Appreciate (👍), Cheers (🎉), Support (🤝), Insight (💡), Curious (🤔), Smile (😊)
  - View reaction counts and lists
  - Update or remove reactions
  
- **Comments**
  - Comment on posts
  - Reply to comments (nested comments)
  - Edit and delete comments
  - Like comments
  
- **User Profiles**
  - Public user profiles with slug-based URLs
  - Display user posts
  - Profile information: first name, last name, bio, photo, location, website, email, phone
  - Edit profile with photo upload (S3 or local storage)

### Admin Panel
- User management (view, create, edit, soft-delete, restore)
- Feed management (view, delete, restore posts and comments)
- Dashboard with basic statistics
- Report management

### Reporting System
- Report users and posts
- Admin moderation tools

> **Note**: This boilerplate does **not** include:
> - Real-time chat
> - Push notifications
> - Firebase integration
> - Subscriptions/payments
> - Company profiles
> - Products/Services
> - Education tracking
> - Profile views tracking
> - Complex user demographics (gender, age group, ethnicity, nationality, marital status)
> - Social media links (LinkedIn, Facebook, Twitter, Instagram, etc.)
> - Location details (city, county, state, zip code, country - only simple "location" field)
> 
> It is intentionally focused on a classic newsfeed + auth + admin use case with a clean, minimal user profile.

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

The boilerplate expects a **minimal `.env`**:

- **Required**:
  - `APP_NAME` (default: "NewsFeed")
  - `APP_ENV` (default: "local")
  - `APP_KEY` (generated with `php artisan key:generate`)
  - `APP_URL` (default: "http://localhost:8000")
  - `DB_CONNECTION` (default: "mysql")
  - `DB_HOST` (default: "127.0.0.1")
  - `DB_PORT` (default: "3306")
  - `DB_DATABASE` (your database name)
  - `DB_USERNAME` (your database username)
  - `DB_PASSWORD` (your database password)

- **Optional** (for S3 storage):
  - `AWS_ACCESS_KEY_ID`
  - `AWS_SECRET_ACCESS_KEY`
  - `AWS_DEFAULT_REGION`
  - `AWS_BUCKET`
  - `AWS_USE_PATH_STYLE_ENDPOINT`

Everything else can be left as defaults or empty.

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

### Step 7: Run Migrations + Seeders

Recommended for a fresh start (drops all tables, recreates them, and seeds):

```bash
php artisan migrate:fresh --seed
```

This creates:
- ✅ Default roles (Admin, Manager, Editor, Member)
- ✅ Admin user: `admin@newsfeed.com` / `12345678`
- ✅ Regular users: `user@newsfeed.com`, `user1@newsfeed.com`, etc. / `12345678`

### Step 8: Build Frontend Assets

For production:

```bash
npm run build
```

For development with hot-reloading:

```bash
npm run dev
```

### Step 9: Start Development Server

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
│   │   │   ├── API/             # API controllers (FeedController, UserController)
│   │   │   ├── Auth/            # Authentication controllers
│   │   │   └── User/            # User-facing controllers (FeedController, UserController)
│   │   └── Middleware/          # Custom middleware (RoleMiddleware)
│   ├── Models/
│   │   ├── Feed/                # Post, PostComment, PostShare, Reaction models
│   │   ├── Reports/             # Report model
│   │   ├── System/              # Role, Permission models
│   │   └── Users/               # User model
│   ├── Services/                # Business logic services (S3Service)
│   └── Helpers/                 # Helper functions (DropDownHelper, GeneralHelper, ImageHelper)
├── database/
│   ├── migrations/              # Database migrations
│   │   ├── users table
│   │   ├── posts table
│   │   ├── post_comments table
│   │   ├── post_shares table
│   │   ├── feed_reactions table
│   │   ├── post_media table
│   │   ├── roles table
│   │   └── reports table
│   └── seeders/                 # Database seeders (UserSeeder, AdminSeeder)
├── resources/
│   ├── views/
│   │   ├── admin/               # Admin panel views
│   │   ├── auth/                # Authentication views (login/register/reset)
│   │   ├── layouts/             # Main layouts (main.blade.php, header.blade.php)
│   │   ├── pages/               # Public pages (home, news-feed)
│   │   └── user/                # User dashboard and profile views
│   ├── js/                      # JavaScript files (App.jsx, bootstrap.js)
│   └── css/                     # CSS files (app.css)
├── routes/
│   ├── web.php                  # Web routes
│   └── api.php                  # API routes
└── public/                      # Public assets (CSS, JS, images)
    └── assets/                  # Static assets
```

## 🔌 API Overview

The application provides RESTful API endpoints for integration:

### Authentication
- `POST /api/register` - Register new user
- `POST /api/login` - Login user
- `POST /api/logout` - Logout user

### User Management
- `GET /api/user/profile/{slug}` - Get user profile
- `PUT /api/user/update/personal` - Update personal details
- `DELETE /api/user/delete` - Delete user account

### Feed Operations
- **Posts**:
  - `GET /api/feed/posts` - Get feed posts (with pagination and sorting)
  - `POST /api/feed/posts` - Create new post
  - `GET /api/feed/posts/{id}` - Get single post
  - `PUT /api/feed/posts/{id}` - Update post
  - `DELETE /api/feed/posts/{id}` - Delete post
  
- **Reactions**:
  - `POST /api/feed/reactions` - Add/update reaction
  - `DELETE /api/feed/reactions` - Remove reaction
  - `GET /api/feed/posts/{id}/reactions-count` - Get reaction count
  - `GET /api/feed/posts/{id}/reactions-list` - Get reactions list
  
- **Comments**:
  - `GET /api/feed/posts/{id}/comments` - Get post comments
  - `POST /api/feed/posts/{id}/comments` - Add comment
  - `PUT /api/feed/comments/{id}` - Update comment
  - `DELETE /api/feed/comments/{id}` - Delete comment
  
- **Shares**:
  - `POST /api/feed/posts/{id}/share` - Share post
  - `GET /api/feed/posts/{id}/shares-list` - Get shares list

### Reports
- `POST /api/report/user` - Report a user
- `POST /api/report/post` - Report a post

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

For development with hot-reloading:

```bash
npm run dev
```

In another terminal, start the Laravel server:

```bash
php artisan serve
```

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

### Issue: "Column not found" errors

**Solution**: Make sure you've run migrations:
```bash
php artisan migrate:fresh --seed
```

### Issue: Build errors with Firebase/chat components

**Solution**: All Firebase and chat components have been removed. If you see build errors, make sure you've run:
```bash
npm run build
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
- **Frontend**: Blade templates, modern CSS/JS, React (minimal, for compatibility)
- **Database**: MySQL/MariaDB
- **Storage**: Local disk by default, AWS S3 optional
- **Authentication**: Laravel Sanctum
- **Queue**: Optional (used only if you enable queued emails / background jobs)

## 🗄️ Database Schema

### Core Tables
- `users` - User accounts (first_name, last_name, email, password, phone, bio, photo, location, website, slug, role_id, status)
- `roles` - User roles (Admin, Manager, Editor, Member)
- `posts` - Newsfeed posts (content, visibility, status, user_id)
- `post_media` - Post images/videos (post_id, media_type, media_url)
- `post_comments` - Comments on posts (with parent_id for nested replies)
- `post_shares` - Shared/reposted posts (post_id, user_id, original_post_id)
- `feed_reactions` - Reactions on posts and comments (reactionable_type, reactionable_id, user_id, type)
- `reports` - User and post reports (reporter_id, reportable_type, reportable_id, reason, status)

## 🔒 Security

- All passwords are hashed using bcrypt
- CSRF protection enabled
- SQL injection protection via Eloquent ORM
- XSS protection via Blade templating
- Rate limiting on authentication endpoints
- Role-based access control
- Soft deletes for data recovery

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

For support, open an issue in the [GitHub repository](https://github.com/ubaidkodekaizen/laravel-news-feed/issues).

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com)
- Modern UI components and styling
- Community contributors

---

**Made with ❤️ for developers and students**
