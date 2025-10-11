# 🚀 Magodi Private School - Setup Guide

## Quick Setup Instructions

### Step 1: Run the Setup Script
1. Open your web browser
2. Navigate to: `http://localhost/MagodiSecondarySchool/setup.php`
3. Click "Run Setup" and wait for completion
4. You should see "Setup completed successfully!"

### Step 2: Login to the System
After setup is complete, you can login with these credentials:

#### 🔑 Default Login Credentials

**Admin Account:**
- **Username**: `admin`
- **Password**: `admin123`
- **Access**: Full system administration

**Login URL**: `http://localhost/MagodiSecondarySchool/login.php`

### Step 3: Access Different Portals

After logging in, you'll be redirected based on your role:

1. **Admin Portal**: `http://localhost/MagodiSecondarySchool/admin/dashboard.php`
2. **Student Portal**: `http://localhost/MagodiSecondarySchool/student/dashboard.php`
3. **Staff Portal**: `http://localhost/MagodiSecondarySchool/staff/dashboard.php`
4. **Parent Portal**: `http://localhost/MagodiSecondarySchool/parent/dashboard.php`

## 🏫 System Features

### What's Included:
- ✅ Complete database with MSCE subjects
- ✅ Sample data (students, teachers, classes)
- ✅ Facebook page integration
- ✅ School branding
- ✅ All user portals
- ✅ Attendance tracking
- ✅ Results management
- ✅ Fee management
- ✅ Library system

### MSCE Subjects Included:
**Core Subjects**: English, Mathematics, Chichewa, Biology, Chemistry, Physics
**Elective Subjects**: Agriculture, Geography, History, Bible Knowledge, Business Studies
**Additional Subjects**: Computer Studies, Physical Education, Life Skills, Social Studies

## 🔧 Troubleshooting

### If Setup Fails:
1. Make sure WAMP/XAMPP is running
2. Check database credentials in `config/database.php`
3. Ensure MySQL is running
4. Delete `setup_completed.txt` and try again

### If Login Doesn't Work:
1. Make sure setup completed successfully
2. Check database connection
3. Verify admin user was created
4. Try running setup again

### Common Issues:
- **Database connection error**: Check MySQL is running
- **Permission denied**: Check file permissions
- **Table not found**: Run setup script first

## 📱 Facebook Integration

The system is integrated with the school's Facebook page:
- **Facebook Page**: https://www.facebook.com/share/1CsgegFzDj/
- **Social Links**: Available in footer and navigation
- **School Branding**: Dynamic school information

## 🎯 Next Steps After Setup

1. **Login as Admin** (`admin` / `admin123`)
2. **Add Real Data**: Replace sample data with actual school data
3. **Create User Accounts**: Add teachers, students, and parents
4. **Configure Settings**: Update school information
5. **Test Features**: Try all system modules

## 📞 Support

If you encounter any issues:
- Check this guide first
- Verify all prerequisites are met
- Contact technical support if needed

---

**System Version**: 1.0  
**Last Updated**: <?php echo date('Y-m-d'); ?>  
**School**: Magodi Private School  
**Location**: Area 23, Lilongwe, Malawi
