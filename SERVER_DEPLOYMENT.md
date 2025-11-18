# Backend Server Deployment Guide

This guide explains how to deploy the PHP backend API to Hostinger.

## Project Structure

```
portfolio/
├── client/              # React frontend (deployed to public_html via GitHub Actions)
├── server/              # PHP backend (deployed separately to ~/server/)
└── .github/workflows/   # CI/CD for frontend only
```

The frontend (`client/`) is automatically deployed via GitHub Actions to `public_html`.
The backend (`server/`) must be deployed manually to a separate folder.

## Server Structure

The `server/` folder contains a complete PHP REST API with:
- Contact form endpoint
- Email notifications
- Rate limiting
- Input validation
- CORS support

## Deployment Steps

### 1. Upload Server Files to Hostinger

Use FTP/SFTP or File Manager to upload the `server/` folder:

```
/home/u102125202/
├── public_html/          # Your React app (already deployed via GitHub Actions)
└── server/               # Upload this folder here
    ├── api/
    ├── config/
    ├── utils/
    ├── index.php
    └── .htaccess
```

### 2. Set Correct Permissions

Via SSH or File Manager, set permissions:

```bash
cd ~/server
chmod 755 api config utils
chmod 644 index.php .htaccess
chmod 644 api/*.php config/*.php utils/*.php
mkdir -p logs/contacts logs/ratelimit
chmod 755 logs
chmod 755 logs/contacts logs/ratelimit
```

### 3. Configure Environment

Copy `.env.example` to `.env` and update:

```bash
cd ~/server
cp .env.example .env
```

Edit `.env` with your settings (optional for basic setup - defaults work fine).

### 4. Test the API

Visit these URLs to verify:

**Health Check:**
```
https://warrdev.tech/server/api/health
```

Should return:
```json
{
  "success": true,
  "message": "API is healthy",
  "data": {
    "status": "ok",
    "version": "v1"
  }
}
```

**Test Contact Form** (use Postman or curl):
```bash
curl -X POST https://warrdev.tech/server/api/contact \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "message": "This is a test message from the API"
  }'
```

### 5. Verify Email Delivery

After submitting the test, check:
1. Your email at `warrdev08@gmail.com`
2. Spam folder if not in inbox
3. Server logs: `server/logs/contacts/`

### 6. Update Client Configuration

The client is already configured to use:
```
https://warrdev.tech/server/api/contact
```

This is set in the Contact component's default API_URL.

## File Structure Details

```
server/
├── api/
│   ├── contact.php      # POST /api/contact
│   └── health.php       # GET /api/health
├── config/
│   └── config.php       # Server settings
├── utils/
│   ├── cors.php         # CORS handling
│   ├── validator.php    # Input validation
│   └── ratelimit.php    # Rate limiting
├── logs/                # Auto-created
│   ├── contacts/        # Email logs
│   ├── ratelimit/       # Rate limit data
│   └── error.log        # PHP errors
├── index.php            # API router
├── .htaccess            # Apache config
└── .env                 # Environment vars (create from .env.example)
```

## Security Checklist

- [x] `.htaccess` protects config files
- [x] Rate limiting prevents spam (10 req/min)
- [x] Input validation & sanitization
- [x] CORS whitelist configured
- [x] Error logging enabled
- [x] Directory browsing disabled

## Email Configuration

### Default Setup (PHP mail)
Works out of the box but may go to spam. Emails send to `warrdev08@gmail.com`.

### Better Deliverability (Optional)

**Option 1: Use SMTP**
Update `server/config/config.php` to use SMTP instead of PHP mail() - requires PHPMailer library.

**Option 2: Use SendGrid/Mailgun**
Integrate email service API for professional delivery.

**Option 3: Gmail App Password**
1. Enable 2FA on Gmail
2. Generate App Password: https://myaccount.google.com/apppasswords
3. Add to `.env`:
   ```
   SMTP_USER=warrdev08@gmail.com
   SMTP_PASS=your_app_password
   ```

## Troubleshooting

### Contact form not sending emails

**Check 1: PHP mail enabled**
```bash
php -i | grep mail
```

**Check 2: Error logs**
```bash
cat ~/server/logs/error.log
tail -f ~/server/logs/error.log  # Live monitoring
```

**Check 3: Contact logs**
```bash
ls -la ~/server/logs/contacts/
cat ~/server/logs/contacts/$(date +%Y-%m).log
```

### CORS errors in browser console

**Fix:** Add your domain to `ALLOWED_ORIGINS` in `server/config/config.php`:
```php
define('ALLOWED_ORIGINS', [
    'http://localhost:5173',
    'https://warrdev.tech',
    'https://www.warrdev.tech'
]);
```

### 500 Internal Server Error

1. Check PHP version: `php -v` (need 7.4+)
2. Check file permissions (see step 2 above)
3. Review error log: `server/logs/error.log`
4. Enable display_errors temporarily in `config/config.php`

### Rate limit too strict

Edit `server/config/config.php`:
```php
define('RATE_LIMIT', 20); // Increase from 10 to 20
```

Then clear rate limit files:
```bash
rm -rf ~/server/logs/ratelimit/*
```

## Testing Locally

Before deploying, test locally:

```bash
cd server
php -S localhost:8000
```

Then test:
```bash
curl http://localhost:8000/api/health
```

Update client `.env.local`:
```
VITE_API_URL=http://localhost:8000/api/contact
```

## Monitoring

### View Recent Contact Submissions
```bash
tail -20 ~/server/logs/contacts/$(date +%Y-%m).log
```

### Monitor Errors in Real-Time
```bash
tail -f ~/server/logs/error.log
```

### Check Rate Limit Status
```bash
ls -la ~/server/logs/ratelimit/
```

## Next Steps

Once deployed and tested:

1. Test contact form on live site: https://warrdev.tech/#contact
2. Submit a test message
3. Verify email received
4. Check logs for any errors
5. Monitor for a few days

## Support

If you encounter issues:
1. Check logs: `server/logs/error.log`
2. Review [server/README.md](server/README.md) for detailed API docs
3. Test API endpoints individually using curl/Postman

---

**Location of server files on your machine:**
`c:\Users\Predator Helios 300\projects\portfolio\server\`

**Upload to Hostinger:**
Via SFTP (port 65002) or File Manager in hPanel.
