# Social Media Settings & Notifications Feature

## Overview
This document describes the social media management system and notification comparison between STU Alumni and Fabamall projects.

---

## ✅ Social Media Settings Feature (IMPLEMENTED)

### What Was Added:

#### 1. **Database Structure**
- Created `site_settings` table to store dynamic site configuration
- Supports multiple setting types: `text`, `url`, `email`, `phone`, `textarea`, `image`
- Settings organized by groups: `social_media`, `contact`, `general`

#### 2. **Model: `SiteSetting.php`**
Location: `app/Models/SiteSetting.php`

**Features:**
- Static helper methods: `SiteSetting::get($key, $default)`, `SiteSetting::set($key, $value)`
- Group-based retrieval: `SiteSetting::getByGroup($group)`
- Audit logging support via `Auditable` trait

#### 3. **Admin Controller: `SettingsController.php`**
Location: `app/Http/Controllers/Admin/SettingsController.php`

**Routes:**
- `GET /admin/settings` - View settings page
- `PUT /admin/settings` - Update settings

**Validation:**
- URL format validation for social media links
- Email format validation for contact email
- Phone number validation

#### 4. **Admin Settings Page**
Location: `resources/views/admin/settings/index.blade.php`

**Sections:**
1. **Social Media Links**
   - Facebook
   - Twitter/X
   - LinkedIn
   - Instagram
   - YouTube (optional)

2. **Contact Information**
   - Email address
   - Phone number
   - Physical address

3. **Live Preview**
   - See how social icons will appear
   - Save changes with single click

#### 5. **Dynamic Footer**
Location: `resources/views/layouts/app.blade.php` (updated)

**Changes:**
- Footer now reads social media links from database
- Only displays icons for links that are set
- Contact information is dynamic
- Links open in new tab with `target="_blank" rel="noopener noreferrer"`

#### 6. **Navigation Updates**
- Added "Settings" link to admin navigation bar
- Icon: `<i class="fas fa-cog"></i>`
- Route-based active state highlighting

---

## 📊 Notification System Comparison

### STU Alumni Project (Current State)

#### Existing Features:
```php
// Model: app/Models/Notification.php
class Notification extends Model
{
    // Types
    const TYPE_REGISTRATION = 'registration';
    const TYPE_VERIFICATION = 'verification';
    const TYPE_EVENT = 'event';
    const TYPE_NEWSLETTER = 'newsletter';
    
    // Status
    const STATUS_PENDING = 'pending';
    const STATUS_SENT = 'sent';
    const STATUS_FAILED = 'failed';
    
    // Via
    const VIA_EMAIL = 'email';
    const VIA_SMS = 'sms';
}
```

**Purpose:** Logging/tracking of sent notifications (email/SMS)  
**Type:** Database logging system  
**User Visibility:** ❌ No (backend only)

#### What's Available:
✅ Laravel's built-in notification system  
✅ Email notifications (`AlumniRegistered.php`)  
✅ Notification logging to database  
❌ **No in-app notifications**  
❌ **No real-time notifications**  
❌ **No notification bell/dropdown**

---

### Fabamall Project (Reference)

#### Advanced Features:
```php
// Features in Fabamall:
1. NotificationController - User-facing notification management
2. AdminNotificationService - Admin notification creation
3. NotificationToken - Push notification support
4. Real-time notifications
5. Notification dropdown in navbar
6. Mark as read/unread functionality
7. Notification preferences
```

**Type:** Full-featured notification system  
**User Visibility:** ✅ Yes (in-app notifications)

#### What Fabamall Has:
✅ In-app notification dropdown  
✅ Real-time push notifications  
✅ Notification preferences  
✅ Mark as read/unread  
✅ Notification center page  
✅ Admin can create custom notifications  
✅ Notification tokens for push

---

## 🎯 Recommendations for STU Alumni

### Priority 1: Add In-App Notifications
**What's Needed:**
1. Database table for user notifications
2. Notification controller for alumni
3. Notification dropdown component in navbar
4. Mark as read functionality

**Benefits:**
- Alumni can see announcements in-app
- Event reminders visible immediately
- Better user engagement

### Priority 2: Real-Time Notifications (Optional)
**What's Needed:**
1. Broadcasting setup (Laravel Echo + Pusher/Socket.io)
2. WebSocket configuration
3. Real-time event listeners

**Benefits:**
- Instant notification delivery
- Modern user experience
- No page refresh needed

### Priority 3: Push Notifications (Advanced)
**What's Needed:**
1. Service Worker setup
2. Push notification tokens
3. Firebase Cloud Messaging (FCM) or similar
4. Browser permission handling

**Benefits:**
- Notifications even when site is closed
- Event reminders reach alumni immediately
- Higher engagement rates

---

## 📝 Default Settings Installed

### Social Media Links (Editable by Admin)
```
Facebook:  https://facebook.com/stualumni
Twitter:   https://twitter.com/stualumni
LinkedIn:  https://linkedin.com/company/stu-alumni
Instagram: https://instagram.com/stualumni
YouTube:   (Empty - optional)
```

### Contact Information (Editable by Admin)
```
Email:   alumni@stu.edu.gh
Phone:   +233 (0) 35 209 1234
Address: Alumni Office, Sunyani Technical University
```

---

## 🚀 How to Use

### For Admins:

1. **Access Settings:**
   - Login as admin
   - Click "Settings" in navigation bar
   - Or visit: `/admin/settings`

2. **Update Social Media:**
   - Enter full URLs (including https://)
   - Leave empty to hide icons
   - Click "Save Settings"

3. **Update Contact Info:**
   - Update email, phone, or address
   - Changes appear immediately on site
   - All changes are logged for audit

### For Developers:

1. **Get a Setting:**
   ```php
   $facebook = SiteSetting::get('facebook_url');
   $email = SiteSetting::get('contact_email', 'default@example.com');
   ```

2. **Set a Setting:**
   ```php
   SiteSetting::set('new_key', 'value', SiteSetting::TYPE_TEXT, SiteSetting::GROUP_GENERAL);
   ```

3. **Get All Settings in Group:**
   ```php
   $socialMedia = SiteSetting::getByGroup(SiteSetting::GROUP_SOCIAL_MEDIA);
   ```

---

## 🔐 Security Features

1. **Audit Logging:**
   - All changes are logged via `Auditable` trait
   - Track who changed what and when

2. **URL Validation:**
   - Social media links validated as proper URLs
   - Email addresses validated for correct format

3. **Admin-Only Access:**
   - Settings page protected by auth middleware
   - Only admin users can access

4. **XSS Protection:**
   - All output properly escaped
   - Safe HTML rendering for addresses

---

## 📁 Files Created/Modified

### New Files:
```
app/Models/SiteSetting.php
app/Http/Controllers/Admin/SettingsController.php
database/migrations/2026_01_01_create_site_settings_table.php
resources/views/admin/settings/index.blade.php
```

### Modified Files:
```
routes/web.php - Added settings routes
resources/views/layouts/app.blade.php - Dynamic footer & admin nav
```

---

## 🎨 UI Features

1. **Color-Coded Icons:**
   - Facebook: Blue
   - Twitter: Sky Blue
   - LinkedIn: Dark Blue
   - Instagram: Pink
   - YouTube: Red

2. **Live Preview:**
   - See changes before saving
   - Icon display preview

3. **Responsive Design:**
   - Works on mobile and desktop
   - Sticky sidebar on desktop

4. **User-Friendly:**
   - Clear labels and placeholders
   - Helpful tips and instructions
   - Success/error messages

---

## 🔄 Next Steps (Future Enhancements)

### Phase 1: Basic In-App Notifications
- [ ] Create notifications table for users
- [ ] Add notification dropdown to navbar
- [ ] Implement mark as read functionality
- [ ] Create notification center page

### Phase 2: Enhanced Notifications
- [ ] Add notification preferences
- [ ] Email notification toggle
- [ ] Notification categories
- [ ] Batch notifications

### Phase 3: Real-Time Features
- [ ] Setup Laravel Broadcasting
- [ ] Implement WebSocket connection
- [ ] Real-time notification delivery
- [ ] Online status indicators

### Phase 4: Push Notifications
- [ ] Setup Service Worker
- [ ] Implement FCM
- [ ] Browser push notifications
- [ ] Mobile app notifications

---

## 📞 Support

For questions or issues:
- Check audit logs in database
- Review Laravel logs in `storage/logs`
- Test URL formats before saving
- Ensure proper permissions for admin users

---

**Last Updated:** January 1, 2026  
**Version:** 1.0.0  
**Status:** ✅ Deployed and Active

