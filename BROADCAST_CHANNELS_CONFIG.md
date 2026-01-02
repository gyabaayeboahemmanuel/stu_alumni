# Broadcast Channels Configuration Guide

**Date:** January 1, 2026  
**Channels:** Email, SMS, WhatsApp, GekyChat

---

## 📡 Available Broadcast Channels

The STU Alumni Portal supports multiple broadcast channels for sending messages to alumni:

1. **Email** - Always available (Laravel Mail)
2. **SMS** - Requires SMS provider setup
3. **WhatsApp** - Requires configuration in `.env`
4. **GekyChat** - Requires configuration in `.env`

---

## 🔧 Configuration

### Email (Always Enabled)
Email uses Laravel's built-in Mail system. Configure in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@stualumni.test
MAIL_FROM_NAME="${APP_NAME}"
```

### WhatsApp Configuration

Add these variables to your `.env` file:

```env
# WhatsApp API Configuration
WHATSAPP_API_KEY=your_whatsapp_api_key
WHATSAPP_API_URL=https://api.whatsapp-provider.com/v1/send
```

**Example Providers:**
- Twilio WhatsApp API
- WhatsApp Business API
- 360dialog
- ChatAPI

**Example with Twilio:**
```env
WHATSAPP_API_KEY=your_twilio_account_sid:your_twilio_auth_token
WHATSAPP_API_URL=https://api.twilio.com/2010-04-01/Accounts/{AccountSid}/Messages.json
```

### GekyChat Configuration

Add these variables to your `.env` file:

```env
# GekyChat API Configuration
GEKYCHAT_API_KEY=your_gekychat_api_key
GEKYCHAT_API_URL=https://api.gekychat.com/v1/send
```

**Note:** Replace with your actual GekyChat API credentials and endpoint.

---

## 💻 Implementation Details

### Channel Selection

When configuring in `.env`:
- **WhatsApp**: Shows only if `WHATSAPP_API_KEY` and `WHATSAPP_API_URL` are set
- **GekyChat**: Shows only if `GEKYCHAT_API_KEY` and `GEKYCHAT_API_URL` are set
- **All**: Sends via all configured channels (Email + SMS + WhatsApp + GekyChat if configured)

### Phone Number Formatting

The system automatically formats phone numbers:
- Removes spaces, dashes, parentheses
- Adds country code (+233 for Ghana) if missing
- Converts local format (0XX...) to international (+233XX...)

Example:
- Input: `0244 123 456`
- Formatted: `+233244123456`

---

## 🎯 Usage

### In Admin Panel:

1. Navigate to **Admin → Broadcast**
2. Select recipients (all, chapter, year group, custom)
3. Choose channel:
   - **Email** - Send via email only
   - **SMS** - Send via SMS only
   - **WhatsApp** - Send via WhatsApp (if configured)
   - **GekyChat** - Send via GekyChat (if configured)
   - **All** - Send via all configured channels
4. Enter subject and message
5. Click "Send Broadcast"

### Channel Availability:

- If WhatsApp is **not configured**: Option is hidden from UI
- If GekyChat is **not configured**: Option is hidden from UI
- Both show status badges if not configured

---

## 📝 API Integration

### WhatsApp API Example

The controller sends requests like this:

```php
$response = Http::post($apiUrl, [
    'api_key' => $apiKey,
    'to' => $phoneNumber,
    'message' => $message,
]);
```

**Adjust based on your provider's requirements:**

#### For Twilio:
```php
$response = Http::post("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json", [
    'From' => 'whatsapp:+14155238886',
    'To' => 'whatsapp:' . $phoneNumber,
    'Body' => $message,
], [
    'auth' => [$accountSid, $authToken]
]);
```

#### For 360dialog:
```php
$response = Http::withHeaders([
    'D360-API-KEY' => $apiKey,
])->post("https://waba-api.360dialog.io/v1/messages", [
    'to' => $phoneNumber,
    'type' => 'text',
    'text' => ['body' => $message]
]);
```

### GekyChat API Example

```php
$response = Http::withHeaders([
    'Authorization' => 'Bearer ' . $apiKey,
    'Content-Type' => 'application/json',
])->post($apiUrl, [
    'phone' => $phoneNumber,
    'message' => $message,
]);
```

**Adjust based on your GekyChat API documentation.**

---

## 🔒 Security

### Best Practices:

1. **Never commit `.env` file** to version control
2. **Use environment variables** for all API keys
3. **Rotate API keys** regularly
4. **Monitor API usage** to prevent abuse
5. **Rate limit** broadcasts if needed
6. **Validate phone numbers** before sending

### .env Example:

```env
# WhatsApp (Keep these secret!)
WHATSAPP_API_KEY=sk_live_abc123xyz789
WHATSAPP_API_URL=https://api.provider.com/v1/send

# GekyChat (Keep these secret!)
GEKYCHAT_API_KEY=gk_live_def456uvw012
GEKYCHAT_API_URL=https://api.gekychat.com/v1/send
```

---

## 🧪 Testing

### Test Individual Channels:

1. **Email**: Send test broadcast to your email
2. **SMS**: Send test to your phone number
3. **WhatsApp**: Send test to your WhatsApp number
4. **GekyChat**: Send test to your GekyChat number

### Test "All" Channel:

When "All" is selected, system sends via:
- Email (always)
- SMS (if configured)
- WhatsApp (if configured)
- GekyChat (if configured)

---

## 📊 Monitoring

### Broadcast Logs:

All broadcasts are logged in the `notifications` table:
- `sent_via`: Channels used (comma-separated)
- `status`: `sent` or `failed`
- `error_message`: Error details if failed

### Check Logs:

```php
// View recent broadcasts
$broadcasts = Notification::where('type', 'broadcast')
    ->latest()
    ->take(10)
    ->get();

// Check failed broadcasts
$failed = Notification::where('type', 'broadcast')
    ->where('status', 'failed')
    ->get();
```

---

## 🐛 Troubleshooting

### WhatsApp Not Showing:

**Problem:** WhatsApp option not visible in UI

**Solutions:**
1. Check `.env` has both `WHATSAPP_API_KEY` and `WHATSAPP_API_URL`
2. Clear config cache: `php artisan config:clear`
3. Restart server/queue workers
4. Check values are not empty

### GekyChat Not Showing:

**Problem:** GekyChat option not visible in UI

**Solutions:**
1. Check `.env` has both `GEKYCHAT_API_KEY` and `GEKYCHAT_API_URL`
2. Clear config cache: `php artisan config:clear`
3. Restart server/queue workers
4. Check values are not empty

### Sending Fails:

**Problem:** Messages not being sent

**Solutions:**
1. Check API credentials are correct
2. Verify API endpoint URL is correct
3. Check API provider dashboard for errors
4. Review Laravel logs: `storage/logs/laravel.log`
5. Verify phone numbers are correctly formatted
6. Check API rate limits

### Phone Number Issues:

**Problem:** "No phone number found for alumni"

**Solutions:**
1. Ensure alumni have phone numbers in database
2. Check phone number field is populated
3. Verify phone format is acceptable

---

## 📚 API Provider Resources

### WhatsApp:
- [Twilio WhatsApp API](https://www.twilio.com/docs/whatsapp)
- [360dialog](https://www.360dialog.com/docs)
- [WhatsApp Business API](https://developers.facebook.com/docs/whatsapp)

### GekyChat:
- Contact GekyChat for API documentation
- Check your GekyChat dashboard for API details

---

## ✅ Configuration Checklist

- [ ] Email configured in `.env` (MAIL_* variables)
- [ ] WhatsApp API key added to `.env`
- [ ] WhatsApp API URL added to `.env`
- [ ] GekyChat API key added to `.env`
- [ ] GekyChat API URL added to `.env`
- [ ] Config cache cleared
- [ ] Server/queue restarted
- [ ] Test broadcast sent successfully
- [ ] All channels working correctly

---

## 🚀 Next Steps

1. **Get API Credentials:**
   - Sign up with WhatsApp provider
   - Sign up with GekyChat
   - Get API keys and endpoints

2. **Configure .env:**
   - Add all required variables
   - Test with sample credentials

3. **Test Integration:**
   - Send test broadcasts
   - Verify all channels work
   - Check logs for errors

4. **Production:**
   - Use production API keys
   - Monitor usage and costs
   - Set up alerts for failures

---

**Status:** Ready for Configuration  
**Last Updated:** January 1, 2026  
**Version:** 1.0.0

