# Year Groups Feature Documentation

**Date:** January 1, 2026  
**Status:** ✅ Fully Implemented and Deployed

---

## 📋 Overview

The Year Groups feature allows administrators to create and manage alumni groups based on graduation year ranges. Each year group can have social group links (WhatsApp, Telegram, GekyChat) that appear on the alumni dashboard for relevant members.

---

## 🎯 Purpose

- **Organize Alumni:** Group alumni by graduation years for better community building
- **Facilitate Connections:** Provide easy access to social group chats
- **Flexible Grouping:** Support overlapping year ranges and multiple groups
- **Admin Control:** Full CRUD operations for managing year groups

---

## 🗄️ Database Structure

### Table: `year_groups`

```sql
CREATE TABLE year_groups (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    start_year INT NOT NULL,
    end_year INT NOT NULL,
    description TEXT NULL,
    whatsapp_link VARCHAR(255) NULL,
    telegram_link VARCHAR(255) NULL,
    gekychat_link VARCHAR(255) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX (start_year, end_year),
    INDEX (is_active)
);
```

### Default Year Groups Seeded:

1. **Pioneers (1968-1979)**
   - Founding alumni of STU
   
2. **The 80s Generation (1980-1989)**
   - Alumni from the transformative 1980s
   
3. **The 90s Generation (1990-1999)**
   - Alumni from the 1990s
   
4. **Millennium Generation (2000-2009)**
   - Alumni from the new millennium
   
5. **The 2010s Cohort (2010-2019)**
   - Alumni from 2010-2019
   
6. **Recent Graduates (2020+)**
   - Our newest alumni (2020-2030)

---

## 🔧 Model: `YearGroup.php`

### Location
`app/Models/YearGroup.php`

### Key Methods

```php
// Get active year groups
YearGroup::active()->get();

// Get year groups for specific graduation year
YearGroup::forGraduationYear(2015);

// Check if a year falls within group
$group->includesYear(2015);

// Check if group has any social links
$group->hasSocialLinks();

// Get full name with year range
$group->full_name; // "The 2010s Cohort (2010 - 2019)"
```

### Relationships
```php
// Get alumni in this year group (conceptual)
$group->alumni(); // Returns alumni with graduation_year between start_year and end_year
```

---

## 🎮 Admin Controller

### Location
`app/Http/Controllers/Admin/YearGroupController.php`

### Routes

| Method | URL | Route Name | Description |
|--------|-----|------------|-------------|
| GET | `/admin/year-groups` | `admin.year-groups.index` | List all year groups |
| GET | `/admin/year-groups/create` | `admin.year-groups.create` | Show create form |
| POST | `/admin/year-groups` | `admin.year-groups.store` | Store new year group |
| GET | `/admin/year-groups/{id}/edit` | `admin.year-groups.edit` | Show edit form |
| PUT/PATCH | `/admin/year-groups/{id}` | `admin.year-groups.update` | Update year group |
| DELETE | `/admin/year-groups/{id}` | `admin.year-groups.destroy` | Delete year group |
| PATCH | `/admin/year-groups/{id}/toggle-active` | `admin.year-groups.toggle-active` | Toggle active status |

### Validation Rules

```php
'name' => 'required|string|max:255',
'start_year' => 'required|integer|min:1968|max:2030',
'end_year' => 'required|integer|min:1968|max:2030|gte:start_year',
'description' => 'nullable|string|max:500',
'whatsapp_link' => 'nullable|url|max:500',
'telegram_link' => 'nullable|url|max:500',
'gekychat_link' => 'nullable|url|max:500',
'is_active' => 'boolean',
```

### Features

1. **Overlap Detection:** Warns admin if year ranges overlap (but allows it)
2. **URL Validation:** Validates all social media links
3. **Audit Logging:** All changes tracked via `Auditable` trait
4. **Soft Validation:** Flexible rules to accommodate various grouping strategies

---

## 🎨 Admin Views

### 1. Index Page (`admin/year-groups/index.blade.php`)

**Features:**
- List all year groups with pagination
- Display year range and span
- Show social link badges (WhatsApp, Telegram, GekyChat)
- Active/Inactive status badges
- Quick actions: Edit, Toggle Active, Delete
- Info box with usage guidelines

**Visual Elements:**
- Color-coded social media badges
- Hover effects on table rows
- Icon indicators for each group
- Empty state with helpful message

### 2. Create Page (`admin/year-groups/create.blade.php`)

**Sections:**
1. **Basic Information**
   - Group name (required)
   - Start year (required, 1968-2030)
   - End year (required, must be >= start year)
   - Description (optional)

2. **Social Group Links**
   - WhatsApp group link (optional)
   - Telegram group link (optional)
   - GekyChat group link (optional)
   - Tips box with best practices

3. **Status**
   - Active checkbox (default: checked)

**Features:**
- Real-time validation
- Helpful placeholders
- Color-coded icons for each platform
- Tips and guidelines

### 3. Edit Page (`admin/year-groups/edit.blade.php`)

**Same as Create Page but:**
- Pre-populated with existing data
- Update button instead of Create
- Can modify all fields including year ranges

---

## 👥 Alumni Dashboard Integration

### Location
`resources/views/alumni/dashboard.blade.php`

### Display Logic

```php
// In AlumniDashboardController
$yearGroups = YearGroup::forGraduationYear($alumni->graduation_year);
```

### UI Features

**Year Groups Section:**
- Appears after stats, before announcements
- Only shows if alumni has matching year groups
- Purple gradient header for visual distinction
- Grid layout (2 columns on desktop, 1 on mobile)

**Each Year Group Card Shows:**
- Group name and year range
- Description (if available)
- Social group join buttons:
  - WhatsApp (green)
  - Telegram (blue)
  - GekyChat (purple)
- "Links coming soon" message if no links set

**Button Features:**
- Opens in new tab (`target="_blank"`)
- Security attributes (`rel="noopener noreferrer"`)
- Hover effects
- Platform-specific colors and icons

---

## 🎯 Use Cases

### Use Case 1: Creating a Decade Group

**Scenario:** Admin wants to create a group for 2010s graduates

**Steps:**
1. Navigate to Admin → Year Groups
2. Click "Create Year Group"
3. Enter:
   - Name: "The 2010s Cohort"
   - Start Year: 2010
   - End Year: 2019
   - Description: "Alumni from 2010-2019"
4. Add social links (optional)
5. Ensure "Active" is checked
6. Click "Create Year Group"

**Result:** All alumni who graduated between 2010-2019 will see this group on their dashboard

### Use Case 2: Special Interest Group

**Scenario:** Create a group for a specific program cohort

**Steps:**
1. Create year group: "Computer Science 2015-2018"
2. Set years: 2015-2018
3. Add WhatsApp link for CS alumni
4. Description: "Computer Science graduates 2015-2018"

**Result:** CS alumni from those years can join their cohort group

### Use Case 3: Overlapping Groups

**Scenario:** Alumni should see both decade group AND special interest group

**Example:**
- Group 1: "The 2010s Cohort" (2010-2019)
- Group 2: "Engineering Excellence" (2015-2020)

**Result:** An engineer who graduated in 2017 sees BOTH groups on their dashboard

---

## 🔐 Security Features

### Access Control
- **Admin Only:** Only admins can manage year groups
- **Protected Routes:** All admin routes require authentication
- **CSRF Protection:** All forms include CSRF tokens

### Data Validation
- **URL Validation:** All social links validated as proper URLs
- **Year Range Validation:** End year must be >= start year
- **Input Sanitization:** All inputs sanitized and escaped
- **SQL Injection Protection:** Eloquent ORM prevents SQL injection

### Audit Trail
- **Auditable Trait:** All CRUD operations logged
- **Track Changes:** Who changed what and when
- **Database Logs:** Stored in `audit_logs` table

---

## 📱 Responsive Design

### Desktop (lg+)
- 2-column grid for year group cards
- Full navigation visible
- Hover effects enabled
- Expanded descriptions

### Tablet (md)
- 2-column grid maintained
- Responsive padding
- Touch-friendly buttons

### Mobile (sm)
- Single column layout
- Stacked buttons
- Optimized spacing
- Mobile-friendly navigation

---

## 🎨 Visual Design

### Color Scheme

**Platform Colors:**
- WhatsApp: Green (#10B981)
- Telegram: Blue (#3B82F6)
- GekyChat: Purple (#8B5CF6)

**Status Colors:**
- Active: Green badge
- Inactive: Red badge

**UI Elements:**
- Primary: STU Green (#1B5E20)
- Secondary: Purple gradient for year groups section
- Cards: White with gray borders
- Hover: Subtle shadow lift effect

### Icons
- Year Groups: `fas fa-users`
- WhatsApp: `fab fa-whatsapp`
- Telegram: `fab fa-telegram`
- GekyChat: `fas fa-comments`
- Settings: `fas fa-users-cog`

---

## 🔄 Workflow

### Admin Workflow

```
1. Navigate to Year Groups
   ↓
2. Click "Create Year Group"
   ↓
3. Fill in basic information
   ↓
4. Add social group links (optional)
   ↓
5. Set active status
   ↓
6. Save
   ↓
7. Year group appears on matching alumni dashboards
```

### Alumni Experience

```
1. Login to dashboard
   ↓
2. See "Your Year Groups" section
   ↓
3. View groups matching graduation year
   ↓
4. Click "Join WhatsApp/Telegram/GekyChat"
   ↓
5. Redirected to group invite link
   ↓
6. Join group chat
```

---

## 📊 Admin Features

### List View Features
- **Pagination:** 15 groups per page
- **Sorting:** By start year (descending)
- **Quick Actions:** Edit, Toggle, Delete
- **Visual Indicators:** Badges for status and links
- **Empty State:** Helpful message when no groups exist

### Form Features
- **Validation:** Real-time client-side validation
- **Error Messages:** Clear, helpful error messages
- **Placeholders:** Example values in all fields
- **Tips:** Context-sensitive help text
- **Auto-save:** Draft functionality (future enhancement)

### Management Features
- **Toggle Active:** Quick enable/disable without editing
- **Delete Confirmation:** Prevents accidental deletion
- **Overlap Warning:** Alerts about overlapping ranges
- **Bulk Actions:** (Future enhancement)

---

## 🚀 Future Enhancements

### Phase 1: Analytics
- [ ] Track join button clicks
- [ ] Show member counts per group
- [ ] Popular groups dashboard
- [ ] Engagement metrics

### Phase 2: Advanced Features
- [ ] Auto-assign alumni to groups
- [ ] Email notifications for new groups
- [ ] Group chat integration
- [ ] In-app messaging

### Phase 3: Alumni Features
- [ ] Request new year groups
- [ ] Suggest group names
- [ ] Rate group activity
- [ ] Group moderators

---

## 📝 Best Practices

### For Administrators

1. **Naming Convention:**
   - Use descriptive names
   - Include year range in name
   - Be consistent across groups

2. **Year Ranges:**
   - Consider logical groupings (decades, programs)
   - Overlaps are okay for special interest groups
   - Update ranges as needed

3. **Social Links:**
   - Use permanent invite links
   - Test links before saving
   - Update expired links promptly
   - Add links gradually as groups form

4. **Descriptions:**
   - Keep brief but informative
   - Explain group purpose
   - Mention any special focus

5. **Active Status:**
   - Only activate when links are ready
   - Deactivate if group is inactive
   - Don't delete unless necessary

### For Alumni

1. **Joining Groups:**
   - Join groups relevant to your cohort
   - Respect group guidelines
   - Participate actively

2. **Multiple Groups:**
   - You may see multiple groups
   - Join all that interest you
   - Each serves different purposes

---

## 🐛 Troubleshooting

### Issue: Year group not showing on dashboard

**Possible Causes:**
1. Group is not active
2. Alumni graduation year outside group range
3. Cache not cleared

**Solutions:**
1. Check group active status in admin
2. Verify year range includes alumni's graduation year
3. Run `php artisan cache:clear`

### Issue: Social links not working

**Possible Causes:**
1. Invalid URL format
2. Expired invite link
3. Link not saved properly

**Solutions:**
1. Validate URL format (must include https://)
2. Generate new invite link
3. Re-save the year group

### Issue: Overlapping groups causing confusion

**This is by design!**
- Alumni can belong to multiple groups
- Overlaps allow for flexible grouping
- Each group serves different purposes

---

## 📞 Support

### For Admins
- Check audit logs for changes
- Review Laravel logs: `storage/logs/laravel.log`
- Test links before publishing
- Use overlap warnings as guidance

### For Alumni
- Contact admin if groups are missing
- Report broken links
- Suggest new year groups
- Request group descriptions

---

## 📚 Related Documentation

- `SOCIAL_MEDIA_AND_NOTIFICATIONS.md` - Social media settings
- `SESSION_UPDATES_SUMMARY.md` - Recent updates overview
- `MULTI_LOGIN_FEATURE.md` - Authentication system

---

## ✅ Implementation Checklist

- [x] Database migration created
- [x] YearGroup model with methods
- [x] Admin controller with CRUD
- [x] Admin views (index, create, edit)
- [x] Routes configured
- [x] Alumni dashboard integration
- [x] Validation rules
- [x] Audit logging
- [x] Responsive design
- [x] Default data seeded
- [x] Navigation links added
- [x] Documentation complete

---

**Status:** ✅ Feature Complete and Production Ready  
**Last Updated:** January 1, 2026  
**Version:** 1.0.0

