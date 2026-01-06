# Image Migration Script to S3

This script migrates all images from local storage to S3 and updates the database records.

## Prerequisites

1. **S3 Configuration**: Make sure your `.env` file has:
   ```env
   AWS_ACCESS_KEY_ID=your_access_key
   AWS_SECRET_ACCESS_KEY=your_secret_key
   AWS_DEFAULT_REGION=your_region
   AWS_BUCKET=your_bucket_name
   AWS_URL=your_custom_url (optional)
   ```

2. **Backup Database**: Always backup your database before running migrations!

## Usage

### Dry Run (Recommended First)
Test the migration without making changes:
```bash
php artisan images:migrate-to-s3 --dry-run
```

### Full Migration
Run the actual migration:
```bash
php artisan images:migrate-to-s3
```

### Migrate Specific Table
Migrate only one table:
```bash
php artisan images:migrate-to-s3 --table=users
php artisan images:migrate-to-s3 --table=products
php artisan images:migrate-to-s3 --table=services
php artisan images:migrate-to-s3 --table=companies
php artisan images:migrate-to-s3 --table=blogs
php artisan images:migrate-to-s3 --table=events
php artisan images:migrate-to-s3 --table=post_media
```

### Force Mode (Skip Confirmations)
```bash
php artisan images:migrate-to-s3 --force
```

### Combined Options
```bash
php artisan images:migrate-to-s3 --table=users --dry-run
php artisan images:migrate-to-s3 --table=products --force
```

## Tables Processed

The script processes the following tables:

1. **users** - `photo` field → `media/images/profile/`
2. **products** - `product_image` field → `media/images/product/`
3. **services** - `service_image` field → `media/images/service/`
4. **companies** - `company_logo` field → `media/images/company/`
5. **blogs** - `image` field → `media/images/blog/`
6. **events** - `image` field → `media/images/event/`
7. **post_media** - `media_path` field → `media/images/posts/` or `media/videos/posts/`

## What the Script Does

1. ✅ Scans all database tables for image fields
2. ✅ Checks if files exist in local storage (`storage/app/public/`)
3. ✅ Skips records that already have S3 URLs
4. ✅ Uploads local files to S3 with proper folder structure
5. ✅ Updates database records with new S3 URLs
6. ✅ Provides detailed progress and summary

## Output Example

```
🚀 Starting image migration to S3...

✅ S3 Configuration: Bucket = your-bucket-name
📋 Processing table: users
------------------------------------------------------------
Found 150 users with photos
  ✅ [ID:1] Uploaded: profile_photos/user1.jpg → https://bucket.s3.amazonaws.com/media/images/profile/1234567890_abc123.jpg
  ✅ [ID:2] Uploaded: profile_photos/user2.jpg → https://bucket.s3.amazonaws.com/media/images/profile/1234567891_def456.jpg
  ⏭️  [ID:3] Already S3 URL: https://bucket.s3.amazonaws.com/media/images/profile/...
  ❌ [ID:4] File not found: profile_photos/missing.jpg

📊 Migration Summary:
============================================================
| Metric                    | Count |
|---------------------------|-------|
| Total Records Found       | 150   |
| Processed                 | 150   |
| Successfully Uploaded     | 145   |
| Already on S3             | 3     |
| Skipped (File not found)  | 2     |
| Errors                    | 0     |

✅ Successfully migrated 145 images to S3!
```

## Notes

- Files that already have S3 URLs (starting with `http://` or `https://`) are skipped
- Files not found in local storage are skipped (error logged)
- The original local files are NOT deleted (you can clean them up manually after verifying)
- The script handles both old local storage paths and new S3 URLs

## Troubleshooting

### "File not found" errors
- Check if files exist in `storage/app/public/`
- Verify the database path format matches the actual file structure

### "S3 configuration is missing" error
- Verify `.env` file has all required AWS credentials
- Run `php artisan config:clear` after updating `.env`

### Upload failures
- Check S3 bucket permissions
- Verify AWS credentials are correct
- Check network connectivity

## Safety Features

- **Dry run mode**: Test without making changes
- **Table-specific migration**: Process one table at a time
- **Skip already migrated**: Automatically skips records with S3 URLs
- **Detailed logging**: Shows exactly what's being processed
- **Error handling**: Continues processing even if individual files fail

