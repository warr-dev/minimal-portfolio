# Portfolio Backend API

PHP-based REST API for the Warren Dalawampu portfolio website.

## Features

- ✅ Contact form submission
- ✅ Email notifications
- ✅ Rate limiting (10 requests/minute)
- ✅ Input validation and sanitization
- ✅ CORS support
- ✅ Error logging
- ✅ Health check endpoint

## Directory Structure

```
server/
├── api/
│   ├── contact.php      # Contact form endpoint
│   └── health.php       # Health check endpoint
├── config/
│   └── config.php       # Server configuration
├── utils/
│   ├── cors.php         # CORS handling
│   ├── validator.php    # Input validation
│   └── ratelimit.php    # Rate limiting
├── logs/                # Auto-generated logs
│   ├── contacts/        # Contact submissions
│   └── ratelimit/       # Rate limit tracking
├── index.php            # API router
├── .htaccess            # Apache config
└── .env.example         # Environment variables template
```

## API Endpoints

### Health Check
```
GET /api/health
```

Response:
```json
{
  "success": true,
  "message": "API is healthy",
  "data": {
    "status": "ok",
    "version": "v1",
    "timestamp": "2025-01-18T12:00:00+08:00"
  }
}
```

### Contact Form
```
POST /api/contact
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "message": "Hello, I'd like to discuss a project..."
}
```

Response (Success):
```json
{
  "success": true,
  "message": "Message sent successfully! I will get back to you soon.",
  "data": {}
}
```

Response (Error):
```json
{
  "success": false,
  "errors": {
    "email": "Invalid email format",
    "message": "Message must be between 10 and 1000 characters"
  }
}
```

## Installation

### Local Development

1. **Requirements:**
   - PHP 7.4 or higher
   - Apache with mod_rewrite enabled

2. **Setup:**
   ```bash
   cd server
   php -S localhost:8000
   ```

3. **Test the API:**
   ```bash
   curl http://localhost:8000/api/health
   ```

### Hostinger Deployment

1. **Upload Files:**
   - Upload `server/` folder to your Hostinger account
   - Place it alongside or inside your `public_html` directory

2. **Configuration:**
   - Copy `.env.example` to `.env` and update values
   - Ensure `logs/` directory is writable: `chmod 755 logs`

3. **Apache Setup:**
   - The `.htaccess` file handles routing automatically
   - Ensure mod_rewrite is enabled (usually enabled by default on Hostinger)

4. **Test:**
   ```
   https://warrdev.tech/server/api/health
   ```

## Security Features

### Rate Limiting
- 10 requests per minute per IP address
- Prevents spam and abuse

### Input Validation
- Email format validation
- String length limits
- HTML/SQL injection prevention
- XSS protection via sanitization

### CORS
- Whitelisted origins only
- Prevents unauthorized access from unknown domains

### File Protection
- Config files protected via .htaccess
- Logs directory not publicly accessible
- Directory browsing disabled

## Email Configuration

By default, uses PHP's `mail()` function. For better deliverability:

1. **Use SMTP (Recommended):**
   - Update `.env` with Gmail App Password or other SMTP credentials
   - Or use a service like SendGrid, Mailgun, or AWS SES

2. **Gmail Setup:**
   - Enable 2-factor authentication
   - Generate App Password: https://myaccount.google.com/apppasswords
   - Use App Password in `.env`

## Logs

### Contact Submissions
- Location: `logs/contacts/YYYY-MM.log`
- Format: `[timestamp] Name <email> - Message preview...`

### Error Logs
- Location: `logs/error.log`
- PHP errors and warnings

### Rate Limit
- Location: `logs/ratelimit/*.json`
- Automatic cleanup of old entries

## Troubleshooting

### CORS Errors
- Check that your domain is in `ALLOWED_ORIGINS` in `config/config.php`
- Verify Apache headers module is enabled

### Email Not Sending
- Check PHP mail configuration: `php -i | grep mail`
- Verify SMTP credentials in `.env`
- Check spam folder
- Review `logs/error.log`

### 500 Errors
- Check file permissions (755 for directories, 644 for files)
- Review `logs/error.log`
- Ensure PHP version >= 7.4

### Rate Limit Issues
- Clear rate limit files: `rm -rf logs/ratelimit/*`
- Adjust `RATE_LIMIT` in `config/config.php`

## Future Enhancements

- [ ] Database storage for contact submissions
- [ ] Admin panel to view messages
- [ ] Email templates with HTML
- [ ] SMTP support via PHPMailer
- [ ] Analytics tracking
- [ ] Newsletter subscription
- [ ] File upload support (resume/portfolio items)

## License

Private use for Warren Dalawampu Portfolio.
