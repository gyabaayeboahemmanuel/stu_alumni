# Mail SSL Certificate Configuration

**Issue:** SSL certificate mismatch error when sending emails  
**Solution:** Disable SSL peer verification for development/staging environments

---

## 🔧 Configuration

### Updated `config/mail.php`

The mail configuration now includes SSL stream options to handle certificate mismatches:

```php
'stream' => [
    'ssl' => [
        'verify_peer' => env('MAIL_SSL_VERIFY_PEER', false),
        'verify_peer_name' => env('MAIL_SSL_VERIFY_PEER_NAME', false),
        'allow_self_signed' => env('MAIL_SSL_ALLOW_SELF_SIGNED', true),
    ],
],
```

### .env Configuration

You can control SSL verification via environment variables:

```env
# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=mail.fabamall.gekymedia.com
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=noreply@fabamall.gekymedia.com
MAIL_FROM_NAME="${APP_NAME}"

# SSL Verification (for certificate mismatch issues)
MAIL_SSL_VERIFY_PEER=false
MAIL_SSL_VERIFY_PEER_NAME=false
MAIL_SSL_ALLOW_SELF_SIGNED=true
```

---

## ⚠️ Security Note

**Warning:** Disabling SSL verification reduces security. Use this only when:
- Development/staging environments
- Self-signed certificates
- Internal mail servers
- Certificate mismatches that can't be fixed

**For Production:** Ideally, fix the SSL certificate on the mail server or use a properly configured mail service.

---

## 🔍 The Error

**Before Fix:**
```
Connection could not be established with host "ssl://mail.fabamall.gekymedia.com:465": 
stream_socket_client(): Peer certificate CN=`cp.gekymedia.com' did not match 
expected CN=`mail.fabamall.gekymedia.com'
```

**Cause:** The SSL certificate's Common Name (CN) doesn't match the mail host domain.

**Solution:** Disable peer verification (configured above).

---

## ✅ Testing

After configuration:

1. **Clear Config Cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Test Email:**
   - Go to Admin → Settings
   - Scroll to "Test Notification Channels"
   - Enter test email address
   - Click "Test Email"
   - Should send successfully!

---

## 🔄 Alternative Solutions

### Option 1: Fix Certificate (Recommended for Production)
- Update mail server SSL certificate
- Ensure CN matches mail host domain
- Use proper certificate authority

### Option 2: Use Different Port
- Try port 587 with TLS instead of 465 with SSL
- Update `.env`:
  ```env
  MAIL_PORT=587
  MAIL_ENCRYPTION=tls
  ```

### Option 3: Use Mail Service
- Use SendGrid, Mailgun, AWS SES
- These services handle SSL properly
- Better deliverability

---

**Status:** ✅ Configured for certificate mismatch handling  
**Last Updated:** January 1, 2026

