# 🔑 Login Credentials - Magodi Private School Management System

## 📋 **Complete Login Reference**

### 👑 **ADMIN ACCESS**
| Username | Password | Role | Access Level |
|----------|----------|------|--------------|
| `admin` | `admin123` | Administrator | Full system access |

---

### 👨‍🏫 **TEACHER ACCOUNTS**
| Username | Password | Name | Position |
|----------|----------|------|----------|
| `john.mwale` | `teacher123` | John Mwale | Head Teacher |
| `mary.chisale` | `teacher123` | Mary Chisale | Teacher |
| `peter.banda` | `teacher123` | Peter Banda | Teacher |
| `grace.mkandawire` | `teacher123` | Grace Mkandawire | Teacher |
| `james.phiri` | `teacher123` | James Phiri | Teacher |

**Teacher Access:** Class management, student records, attendance, exam results, limited reports

---

### 🎓 **STUDENT ACCOUNTS**
| Username | Password | Name | Student Number | Class |
|----------|----------|------|----------------|-------|
| `chisomo.mwale` | `student123` | Chisomo Mwale | ST001 | Form 1A |
| `tiyamike.chisale` | `student123` | Tiyamike Chisale | ST002 | Form 1A |
| `kondwani.banda` | `student123` | Kondwani Banda | ST003 | Form 1B |
| `thandiwe.phiri` | `student123` | Thandiwe Phiri | ST004 | Form 1B |
| `blessings.mkandawire` | `student123` | Blessings Mkandawire | ST005 | Form 2A |

**Student Access:** Personal records, exam results, attendance, assignments

---

### 👨‍👩‍👧‍👦 **PARENT/GUARDIAN ACCOUNTS**
| Username | Password | Name | Relationship |
|----------|----------|------|--------------|
| `james.mwale` | `parent123` | James Mwale | Father |
| `sarah.chisale` | `parent123` | Sarah Chisale | Mother |
| `michael.banda` | `parent123` | Michael Banda | Father |
| `patricia.phiri` | `parent123` | Patricia Phiri | Mother |
| `david.mkandawire` | `parent123` | David Mkandawire | Father |

**Parent Access:** Child's academic records, attendance, exam results, fee statements

---

### 👨‍💼 **STAFF ACCOUNTS**
| Username | Password | Role | Access Level |
|----------|----------|------|--------------|
| `accountant` | `staff123` | Accountant | Financial management |
| `librarian` | `staff123` | Librarian | Library management |
| `staff` | `staff123` | General Staff | Basic system access |

---

## 🚀 **How to Access the System**

### **Step 1: Setup Database**
```
http://localhost/MagodiSecondarySchool/quick_setup.php
```

### **Step 2: Create Sample Users**
```
http://localhost/MagodiSecondarySchool/create_sample_users.php
```

### **Step 3: Login to System**
```
http://localhost/MagodiSecondarySchool/simple_login.php
```

---

## 🔐 **Password Security**

### **Default Passwords:**
- **Admin:** `admin123`
- **Teachers:** `teacher123`
- **Students:** `student123`
- **Parents:** `parent123`
- **Staff:** `staff123`

### **Security Notes:**
- ⚠️ **All passwords are case-sensitive**
- 🔄 **Users should change passwords after first login**
- 👑 **Admin can reset any user's password**
- 🛡️ **Each role has different access permissions**

---

## 📱 **User Role Access Levels**

### **👑 Administrator (admin)**
- ✅ Full system access
- ✅ User management
- ✅ System settings
- ✅ All reports and analytics
- ✅ Database management

### **👨‍🏫 Teachers**
- ✅ Student management
- ✅ Attendance marking
- ✅ Exam management
- ✅ Grade entry
- ✅ Class reports
- ❌ Financial data
- ❌ System settings

### **🎓 Students**
- ✅ Personal records
- ✅ Exam results
- ✅ Attendance history
- ✅ Assignments
- ❌ Other students' data
- ❌ Administrative functions

### **👨‍👩‍👧‍👦 Parents/Guardians**
- ✅ Child's academic records
- ✅ Attendance monitoring
- ✅ Exam results
- ✅ Fee statements
- ❌ Other students' data
- ❌ Administrative functions

### **👨‍💼 Staff**
- ✅ Library management (Librarian)
- ✅ Financial records (Accountant)
- ✅ Basic system access (General Staff)
- ❌ Student academic data
- ❌ Administrative functions

---

## 🆘 **Troubleshooting**

### **Login Issues:**
1. **Check username spelling** - All usernames are lowercase
2. **Verify password** - All passwords are case-sensitive
3. **Ensure database is set up** - Run `quick_setup.php` first
4. **Create sample users** - Run `create_sample_users.php`

### **Access Issues:**
1. **Wrong role** - Each user type has different permissions
2. **Account inactive** - Contact admin to activate account
3. **Password forgotten** - Admin can reset passwords

### **System Issues:**
1. **Database connection** - Check database server
2. **File permissions** - Ensure proper file access
3. **Browser cache** - Clear browser cache and cookies

---

## 📞 **Support**

For technical support or password resets, contact the system administrator:
- **Email:** admin@magodischool.mw
- **Phone:** +265 123 456 789
- **System:** Use admin account to manage users

---

**Last Updated:** <?php echo date('Y-m-d H:i:s'); ?>
**System Version:** 1.0
**School:** Magodi Private School, Area 23, Lilongwe, Malawi
