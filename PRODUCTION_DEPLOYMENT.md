# Production Deployment Guide - Queue Emails

## 🚀 Commands to Run on Live Server

### Step 1: Pull/Deploy Code
```bash
# If using Git
git pull origin main

# Or if using deployment tool (Laravel Forge, Envoyer, etc.)
# Follow your deployment process
```

### Step 2: Install Dependencies
```bash
composer install --no-dev --optimize-autoloader
```

### Step 3: Run Migrations (if needed)
```bash
php artisan migrate --force
```

**Note:** The `jobs` table should already exist, but this ensures everything is up to date.

### Step 4: Clear and Cache Configuration
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches (for better performance)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 5: Verify Queue Configuration
```bash
# Check your .env file has:
# QUEUE_CONNECTION=database
```

### Step 6: Start Queue Worker (IMPORTANT!)

You have several options for production:

---

## Option A: Using Supervisor (Recommended for Production)

### 6.1 Install Supervisor (if not installed)
```bash
sudo apt-get update
sudo apt-get install supervisor
```

### 6.2 Create Supervisor Configuration

Create file: `/etc/supervisor/conf.d/muslimlynk-queue.conf`

```ini
[program:muslimlynk-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/project/artisan queue:work database --sleep=3 --tries=3 --max-time=3600 --timeout=90
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/your/project/storage/logs/queue-worker.log
stopwaitsecs=3600
```

**Important:** Replace `/path/to/your/project` with your actual project path!

### 6.3 Update Supervisor
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start muslimlynk-queue-worker:*
```

### 6.4 Check Status
```bash
sudo supervisorctl status muslimlynk-queue-worker:*
```

### 6.5 Useful Supervisor Commands
```bash
# Restart queue workers
sudo supervisorctl restart muslimlynk-queue-worker:*

# Stop queue workers
sudo supervisorctl stop muslimlynk-queue-worker:*

# View logs
tail -f /path/to/your/project/storage/logs/queue-worker.log
```

---

## Option B: Using Systemd (Alternative)

### 6.1 Create Service File

Create file: `/etc/systemd/system/muslimlynk-queue.service`

```ini
[Unit]
Description=MuslimLynk Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /path/to/your/project/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
StandardOutput=append:/path/to/your/project/storage/logs/queue-worker.log
StandardError=append:/path/to/your/project/storage/logs/queue-worker.log

[Install]
WantedBy=multi-user.target
```

### 6.2 Enable and Start
```bash
sudo systemctl daemon-reload
sudo systemctl enable muslimlynk-queue
sudo systemctl start muslimlynk-queue
```

### 6.3 Check Status
```bash
sudo systemctl status muslimlynk-queue
```

---

## Option C: Using Screen/Tmux (Temporary/Testing)

### 6.1 Start Queue Worker in Screen
```bash
screen -S queue-worker
php artisan queue:work database --tries=3
# Press Ctrl+A then D to detach
```

### 6.2 Reattach Later
```bash
screen -r queue-worker
```

**Note:** This is NOT recommended for production - use Supervisor or Systemd instead.

---

## Step 7: Verify Everything is Working

### 7.1 Check Queue Worker is Running
```bash
ps aux | grep "queue:work"
```

You should see the queue worker process running.

### 7.2 Test Email Sending
```bash
php artisan tinker
```

```php
use App\Mail\PasswordReset;
use Illuminate\Support\Facades\Mail;

Mail::to('your-test-email@example.com')->queue(new PasswordReset('test-token'));
exit
```

### 7.3 Check Jobs Table
```bash
php artisan tinker
```

```php
DB::table('jobs')->count(); // Should show queued jobs
exit
```

### 7.4 Monitor Queue Processing
```bash
# Watch jobs being processed
watch -n 1 'php artisan tinker --execute="echo DB::table(\"jobs\")->count();"'
```

### 7.5 Check Logs
```bash
tail -f storage/logs/laravel.log | grep -i queue
```

---

## Step 8: Set Up Log Rotation (Optional but Recommended)

Create: `/etc/logrotate.d/muslimlynk-queue`

```
/path/to/your/project/storage/logs/queue-worker.log {
    daily
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
```

---

## 🔄 After Code Updates (Future Deployments)

When you push new code updates, run:

```bash
# 1. Pull code
git pull origin main

# 2. Install dependencies
composer install --no-dev --optimize-autoloader

# 3. Run migrations (if any)
php artisan migrate --force

# 4. Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 5. Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Restart queue workers (if using Supervisor)
sudo supervisorctl restart muslimlynk-queue-worker:*

# OR (if using Systemd)
sudo systemctl restart muslimlynk-queue
```

---

## 🚨 Troubleshooting

### Issue: Queue worker not processing jobs
**Solution:**
```bash
# Check if worker is running
ps aux | grep "queue:work"

# Check Supervisor status
sudo supervisorctl status muslimlynk-queue-worker:*

# Restart worker
sudo supervisorctl restart muslimlynk-queue-worker:*
```

### Issue: Jobs stuck in queue
**Solution:**
```bash
# Check jobs table
php artisan tinker
DB::table('jobs')->count();
exit

# Clear stuck jobs (if needed)
php artisan queue:flush

# Restart worker
sudo supervisorctl restart muslimlynk-queue-worker:*
```

### Issue: Failed jobs accumulating
**Solution:**
```bash
# View failed jobs
php artisan queue:failed

# Retry all failed jobs
php artisan queue:retry all

# Or retry specific job
php artisan queue:retry {job-id}
```

### Issue: Emails not sending
**Solution:**
1. Check queue worker is running: `ps aux | grep queue:work`
2. Check jobs table: `php artisan tinker` → `DB::table('jobs')->count()`
3. Check failed_jobs: `php artisan queue:failed`
4. Check logs: `tail -f storage/logs/laravel.log`
5. Verify SMTP settings in `.env`

---

## 📋 Quick Reference Checklist

After deploying to production:

- [ ] Code pulled/deployed
- [ ] Dependencies installed (`composer install --no-dev`)
- [ ] Migrations run (`php artisan migrate --force`)
- [ ] Caches cleared and rebuilt
- [ ] `.env` has `QUEUE_CONNECTION=database`
- [ ] Supervisor/Systemd configured
- [ ] Queue worker started and running
- [ ] Test email sent successfully
- [ ] Jobs processing correctly
- [ ] Logs being written
- [ ] Monitoring set up

---

## 🎯 Summary

**Essential Commands for Production:**

```bash
# Initial Setup
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache

# Start Queue Worker (Supervisor)
sudo supervisorctl start muslimlynk-queue-worker:*

# Verify
ps aux | grep "queue:work"
php artisan queue:monitor
```

**Most Important:** The queue worker MUST be running 24/7 for emails to be sent!

---

## 📞 Support

If emails still don't work:
1. Check queue worker is running
2. Check `storage/logs/laravel.log` for errors
3. Check `failed_jobs` table
4. Verify SMTP credentials in `.env`
5. Test with: `php artisan tinker` → queue a test email

