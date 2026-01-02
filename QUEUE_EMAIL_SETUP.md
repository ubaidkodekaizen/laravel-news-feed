# Queue Email Setup Guide

## ✅ What Was Changed

All emails have been converted to use Laravel queues for better reliability and performance:

1. **Created Mailable Classes** (all implement `ShouldQueue`):
   - `app/Mail/PasswordReset.php`
   - `app/Mail/EmailVerification.php`
   - `app/Mail/WelcomeNewUser.php`
   - `app/Mail/ConfirmationEmail.php`
   - `app/Mail/AdminNotification.php`

2. **Updated All Controllers**:
   - All `Mail::send()` calls replaced with `Mail::to()->queue()` or `Mail::queue()`
   - All emails now use Mailable classes that implement `ShouldQueue`

3. **Benefits**:
   - ✅ Emails are processed in background (non-blocking)
   - ✅ Automatic retry on failure
   - ✅ Better error handling
   - ✅ Faster response times
   - ✅ Can handle high email volume

---

## 🚀 Setup Steps

### Step 1: Ensure Jobs Table Exists

The jobs table should already exist from migrations. If not, run:

```bash
php artisan migrate
```

This creates the `jobs` table in your database.

### Step 2: Configure Queue Connection

In your `.env` file, make sure you have:

```env
QUEUE_CONNECTION=database
```

**Options:**
- `database` - Uses database (recommended, already configured)
- `sync` - Processes immediately (for testing)
- `redis` - Uses Redis (faster, requires Redis)
- `sqs` - Uses AWS SQS (for production scaling)

### Step 3: Start Queue Worker

You need to run a queue worker to process emails. Choose one method:

#### Option A: Development (Manual)
```bash
php artisan queue:work
```

#### Option B: Development (Watch Mode - Auto Restart)
```bash
php artisan queue:watch
```

#### Option C: Production (Supervisor - Recommended)
Install Supervisor to keep queue worker running:

1. Install Supervisor:
```bash
sudo apt-get install supervisor  # Ubuntu/Debian
```

2. Create config file: `/etc/supervisor/conf.d/muslimlynk-queue.conf`
```ini
[program:muslimlynk-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/project/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
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

3. Update Supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start muslimlynk-queue-worker:*
```

#### Option D: Using Laravel Horizon (Advanced)
For Redis-based queues with dashboard:
```bash
composer require laravel/horizon
php artisan horizon:install
php artisan horizon
```

---

## 🧪 Testing

### Test 1: Check Queue Connection
```bash
php artisan tinker
```
```php
config('queue.default'); // Should return 'database'
```

### Test 2: Send Test Email
```bash
php artisan tinker
```
```php
use App\Mail\PasswordReset;
use Illuminate\Support\Facades\Mail;

Mail::to('test@example.com')->queue(new PasswordReset('test-token'));
```

### Test 3: Check Jobs Table
```bash
php artisan tinker
```
```php
DB::table('jobs')->count(); // Should show queued jobs
```

### Test 4: Process Queue Manually
```bash
php artisan queue:work --once
```

---

## 📊 Monitoring

### Check Queue Status
```bash
# See pending jobs
php artisan queue:monitor

# See failed jobs
php artisan queue:failed
```

### View Logs
```bash
tail -f storage/logs/laravel.log | grep -i queue
```

### Check Database
```sql
-- See pending jobs
SELECT * FROM jobs;

-- See failed jobs
SELECT * FROM failed_jobs;
```

---

## 🔧 Troubleshooting

### Issue: Emails not sending
**Solution:**
1. Check if queue worker is running: `ps aux | grep queue:work`
2. Check jobs table: `SELECT * FROM jobs;`
3. Check failed_jobs table: `SELECT * FROM failed_jobs;`
4. Start queue worker: `php artisan queue:work`

### Issue: Queue worker stops
**Solution:**
- Use Supervisor (see Step 3, Option C)
- Or use `queue:watch` for auto-restart

### Issue: Jobs stuck in queue
**Solution:**
```bash
# Clear stuck jobs
php artisan queue:flush

# Retry failed jobs
php artisan queue:retry all
```

### Issue: Too many failed jobs
**Solution:**
```bash
# View failed jobs
php artisan queue:failed

# Retry specific job
php artisan queue:retry {job-id}

# Retry all
php artisan queue:retry all

# Clear failed jobs
php artisan queue:flush
```

---

## ⚙️ Configuration Options

### Queue Retry Settings
In your Mailable classes, you can customize retries:

```php
public $tries = 3; // Number of retry attempts
public $timeout = 60; // Timeout in seconds
public $backoff = [10, 30, 60]; // Wait times between retries
```

### Queue Priority
You can prioritize certain emails:

```php
Mail::to($email)->queue(new PasswordReset($token))
    ->onQueue('high-priority');
```

Then process high priority first:
```bash
php artisan queue:work --queue=high-priority,default
```

---

## 🚨 Important Notes

1. **Queue Worker Must Be Running**: Emails won't send if queue worker isn't running
2. **Database Connection**: Uses your default database connection
3. **Failed Jobs**: Check `failed_jobs` table regularly
4. **Production**: Always use Supervisor or similar process manager
5. **Logs**: Monitor `storage/logs/laravel.log` for queue errors

---

## 📝 Quick Start Commands

```bash
# Start queue worker (development)
php artisan queue:work

# Start queue worker with auto-restart (development)
php artisan queue:watch

# Process one job (testing)
php artisan queue:work --once

# Clear all jobs
php artisan queue:flush

# Retry failed jobs
php artisan queue:retry all

# Check queue status
php artisan queue:monitor
```

---

## ✅ Verification Checklist

- [ ] Jobs table exists in database
- [ ] `QUEUE_CONNECTION=database` in `.env`
- [ ] Queue worker is running
- [ ] Test email queued successfully
- [ ] Test email processed and sent
- [ ] Failed jobs table exists
- [ ] Logs show queue activity
- [ ] Production: Supervisor configured (if applicable)

---

## 🎯 Summary

All emails are now queued! They will:
- ✅ Process in background (non-blocking)
- ✅ Retry automatically on failure
- ✅ Log all activity
- ✅ Handle high volume efficiently

**Remember**: Queue worker must be running for emails to be sent!

