# STU Alumni System - Administrator Manual

## ðŸ› ï¸ System Administration Guide

### System Overview
The STU Alumni System is built on Laravel framework with MySQL database. This guide covers administrative functions and system maintenance.

## ðŸ‘¨â€ðŸ’¼ Admin Roles and Permissions

### Role Hierarchy
1. **Super Admin**
   - Full system access
   - User and role management
   - System configuration
   - Database operations

2. **Alumni Admin**
   - Alumni verification and management
   - Business directory approval
   - Basic reporting
   - User support

3. **Content Editor**
   - Announcement management
   - Event creation and management
   - Executive team updates
   - Content moderation

### Permission Matrix
| Function | Super Admin | Alumni Admin | Content Editor |
|----------|-------------|--------------|----------------|
| User Management | âœ… Full | âœ… Limited | âŒ No |
| Alumni Verification | âœ… Full | âœ… Full | âŒ No |
| Content Management | âœ… Full | âŒ No | âœ… Full |
| System Config | âœ… Full | âŒ No | âŒ No |
| Reports | âœ… Full | âœ… Limited | âŒ No |

## ðŸ”§ Administrative Functions

### User Management
**Access**: Admin Dashboard â†’ User Management

**Functions**:
- View all users
- Edit user profiles
- Reset passwords
- Deactivate/activate accounts
- Assign roles

**Best Practices**:
- Regular review of user accounts
- Immediate deactivation of suspicious accounts
- Proper role assignment
- Audit trail maintenance

### Alumni Verification
**Access**: Admin Dashboard â†’ Pending Verification

**Verification Process**:
1. Review applicant information
2. Verify uploaded documents
3. Check against existing records
4. Approve or reject with reason
5. Notify applicant

**Document Verification Guidelines**:
- Acceptable documents: Certificate, Transcript, National ID, Passport
- Documents must be clear and legible
- Information must match registration details
- Reject forged or unclear documents

### Content Management
**Access**: Admin Dashboard â†’ Content Management

**Announcements**:
- Create and publish news
- Categorize appropriately
- Set visibility (Public/Alumni)
- Pin important announcements
- Schedule publications

**Events**:
- Create university events
- Set registration parameters
- Manage attendee lists
- Send event reminders
- Generate attendance reports

### Business Directory Management
**Access**: Admin Dashboard â†’ Business Directory

**Approval Process**:
1. Review business details
2. Verify alumni ownership
3. Check business legitimacy
4. Approve or request changes
5. Feature quality listings

**Moderation Guidelines**:
- Ensure appropriate content
- Verify business authenticity
- Monitor for spam
- Handle user reports

## ðŸ“Š Reporting and Analytics

### Available Reports
1. **Alumni Statistics**
   - Registration trends
   - Verification status
   - Programme distribution
   - Geographic distribution

2. **Engagement Metrics**
   - Active users
   - Event participation
   - Business listings
   - Announcement views

3. **System Usage**
   - Peak usage times
   - Feature popularity
   - Error rates
   - Performance metrics

### Report Generation
**Automated Reports**:
- Weekly registration summary
- Monthly engagement report
- Quarterly system health

**Custom Reports**:
- Use report builder
- Export to CSV/PDF
- Schedule automated delivery

## ðŸ”’ Security Management

### User Security
- Monitor failed login attempts
- Review user activity logs
- Implement password policies
- Manage session timeouts

### Data Security
- Regular database backups
- Secure file upload handling
- SSL/TLS enforcement
- Data encryption at rest

### System Security
- Regular security updates
- Vulnerability scanning
- Access control reviews
- Security header implementation

## ðŸš€ System Maintenance

### Daily Tasks
1. **System Health Check**
   \\\ash
   ./deploy-scripts/health-check.sh
   \\\

2. **Monitor Logs**
   \\\ash
   tail -f storage/logs/laravel.log
   \\\

3. **Queue Management**
   \\\ash
   php artisan queue:work
   \\\

### Weekly Tasks
1. **Database Backup**
   \\\ash
   ./deploy-scripts/backup-db.sh
   \\\

2. **Cleanup Operations**
   \\\ash
   php artisan alumni:cleanup-pending
   php artisan auth:clear-resets
   \\\

3. **Performance Optimization**
   \\\ash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   \\\

### Monthly Tasks
1. **Security Audit**
   - Review user access
   - Check system logs
   - Update dependencies
   - Security patch application

2. **Data Analysis**
   - Growth metrics
   - User engagement
   - System performance
   - Feature usage

## ðŸ†˜ Troubleshooting Guide

### Common Issues

**Performance Issues**
- Check server resources
- Review database queries
- Clear application cache
- Optimize images and assets

**Email Delivery Problems**
- Verify SMTP settings
- Check queue workers
- Review email logs
- Test with different providers

**File Upload Issues**
- Check file size limits
- Verify directory permissions
- Review file type restrictions
- Test with different file types

**Database Connection Issues**
- Verify database service
- Check connection credentials
- Review database logs
- Test connection manually

### Emergency Procedures

**System Outage**
1. Check server status
2. Review error logs
3. Restart services if needed
4. Notify stakeholders

**Security Breach**
1. Isolate affected systems
2. Preserve evidence
3. Reset compromised accounts
4. Security patch implementation

**Data Loss**
1. Restore from backup
2. Identify cause
3. Implement prevention measures
4. Communicate with affected users

## ðŸ“ž Support Contacts

### Technical Support
- **System Administrator**: [Admin Contact]
- **IT Directorate**: it.directorate@stu.edu.gh
- **Emergency Hotline**: [Emergency Phone]

### Vendor Support
- **Hosting Provider**: [Provider Contact]
- **Domain Registrar**: [Registrar Contact]
- **SSL Certificate**: [SSL Provider]

### Maintenance Schedule
- **Regular Maintenance**: Sundays 2:00 AM - 4:00 AM
- **Emergency Maintenance**: As needed with notification
- **System Updates**: Monthly security patches

---
**Document Version**: 1.0  
**Last Updated**: 2025-11-13  
**For System Version**: 1.0+
