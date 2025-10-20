# 🔧 Fix Student Login Issue - Instructions

## Problem
Student credentials displayed on the admin page show passwords like `student2025315`, but these passwords don't work for login because:
1. The `student_credentials` table doesn't exist or is empty
2. The actual passwords in the `user` table are different/hashed incorrectly
3. The credentials were never properly saved

## Solution

### Step 1: Run the Fix Script
Open your browser and navigate to:
```
http://localhost/MagodiSecondarySchool/fix_student_credentials.php
```

This script will:
- ✅ Create the `student_credentials` table (if it doesn't exist)
- ✅ Generate fresh login credentials for all active students
- ✅ Save both the hashed passwords (in `user` table) and original passwords (in `student_credentials` table)
- ✅ Display all the new credentials

### Step 2: Save/Print the Credentials
After running the fix script, you'll see a table with all student credentials showing:
- Student Name
- Student Number
- Class
- **Username** (e.g., `chisomo.mwale`)
- **Password** (e.g., `student2025456`)

**Save or print these credentials immediately!**

### Step 3: Test the Login
1. Go to: `http://localhost/MagodiSecondarySchool/simple_login.php`
2. Enter the username and password from Step 2
3. Click Login
4. You should be redirected to the student dashboard

### Step 4: View All Credentials Anytime
You can view all credentials at:
```
http://localhost/MagodiSecondarySchool/view_credentials.php
```

Or for individual students through admin:
```
http://localhost/MagodiSecondarySchool/admin/student_credentials.php?student_id=1
```

## What Was Fixed

### 1. Created `student_credentials` Table
```sql
CREATE TABLE student_credentials (
    Credential_ID INT PRIMARY KEY AUTO_INCREMENT,
    Student_ID INT NOT NULL,
    Username VARCHAR(100) NOT NULL,
    Original_Password VARCHAR(100) NOT NULL,
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (Student_ID) REFERENCES student(Student_ID),
    UNIQUE KEY unique_student (Student_ID)
);
```

### 2. Updated Credential Generation
- Generates proper username format: `firstname.lastname`
- Creates strong password: `studentYEARxxx` (e.g., `student2025456`)
- Hashes password for `user` table (secure storage)
- Stores original password in `student_credentials` table (for display)

### 3. Updated `view_credentials.php`
Now fetches actual passwords from `student_credentials` table instead of showing hardcoded `student123`

## Files Modified

1. ✅ `fix_student_credentials.php` - NEW: Main fix script
2. ✅ `view_credentials.php` - UPDATED: Shows real passwords
3. ✅ `test_student_login.php` - NEW: Diagnostic tool
4. ✅ `admin/student_credentials.php` - Already correct ✓

## Common Issues & Solutions

### Issue: "Invalid username or password"
**Solution:** Run `fix_student_credentials.php` again to regenerate credentials

### Issue: "Username already exists"
**Solution:** The script handles this automatically by updating existing accounts

### Issue: "Table doesn't exist"
**Solution:** The fix script creates the table automatically

### Issue: Student sees wrong password
**Solution:** Use the password from `fix_student_credentials.php` or `view_credentials.php`, not from memory

## Password Format

Student passwords follow this pattern:
```
student + YEAR + 3-digit random number
Example: student2025456
```

Teachers have different credentials stored in `teacher_credentials` table.

## Security Notes

⚠️ **Important:**
- All passwords are case-sensitive
- Students should change passwords after first login
- Original passwords are stored for recovery purposes only
- Admin can regenerate credentials anytime

## Quick Links

After fixing, use these links:

| Purpose | URL |
|---------|-----|
| Test Login | `simple_login.php` |
| View All Credentials | `view_credentials.php` |
| Admin Student Credentials | `admin/student_credentials.php?student_id=X` |
| Fix/Regenerate | `fix_student_credentials.php` |
| Diagnostic Tool | `test_student_login.php` |

## Need Help?

If login still doesn't work after running the fix:

1. Open browser console (F12)
2. Go to `test_student_login.php`
3. Enter username: `chisomo.mwale`
4. Check if:
   - User exists in database
   - Password hash matches
   - User is active

Contact your system administrator if issues persist.

