# Magodi Private School Management System

A comprehensive School Management System (SMS) for Magodi Private School located in Area 23, Lilongwe, Malawi.

## 🏫 Features

### Core Modules
- **Student Management** - Complete student records, admission, and academic tracking
- **Staff/Teacher Management** - Teacher and non-teaching staff management
- **Class & Subject Management** - Class organization and MSCE subject allocation
- **Timetable & Attendance** - Class scheduling and attendance tracking
- **Examination & Results** - Exam management and result processing
- **Fees & Finance** - Fee structure and payment management
- **Library Management** - Book catalog and issue tracking
- **Inventory & Assets** - School asset management
- **Parent/Guardian Portal** - Parent access to student information
- **Communication & Notifications** - Messaging system
- **Reports & Analytics** - Comprehensive reporting system

### Malawi-Specific Features
- **MSCE Subject Support** - Complete Malawi School Certificate of Education subject structure
- **Core Subjects**: English, Mathematics, Chichewa, Biology, Chemistry, Physics
- **Elective Subjects**: Agriculture, Geography, History, Bible Knowledge, Business Studies
- **Facebook Integration** - Direct integration with school's Facebook page
- **Malawi Curriculum** - Aligned with Malawi National Examinations Board (MANEB)

### User Portals
- **Admin Portal** - Complete system administration
- **Student Portal** - Student dashboard and academic information
- **Staff Portal** - Teacher and staff management tools
- **Parent Portal** - Parent access to child's information

## 🚀 Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- WAMP/XAMPP (for local development)

### Setup Instructions

1. **Clone/Download the project**
   ```bash
   git clone [repository-url]
   # or download and extract the ZIP file
   ```

2. **Place in web directory**
   - For WAMP: Place in `C:\wamp64\www\MagodiSecondarySchool`
   - For XAMPP: Place in `C:\xampp\htdocs\MagodiSecondarySchool`

3. **Configure Database**
   - Open `config/database.php`
   - Update database credentials if needed:
     ```php
     private $host = "localhost";
     private $db_name = "magodi_private_school";
     private $username = "root";
     private $password = "";
     ```

4. **Run Setup**
   - Open your browser and navigate to: `http://localhost/MagodiSecondarySchool/setup.php`
   - The setup will:
     - Create the database
     - Create all required tables
     - Insert sample data
     - Create default admin account

5. **Access the System**
   - Default admin credentials:
     - Username: `admin`
     - Password: `admin123`
   - Login at: `http://localhost/MagodiSecondarySchool/login.php`

## 📊 Database Schema

The system includes the following main entities:

### Core Entities
- **Student** - Student information and academic records
- **Teacher** - Teaching staff information
- **Class** - Class organization and management
- **Subject** - Subject catalog and management
- **Guardian** - Parent/guardian information
- **User** - System user accounts and authentication

### Academic Entities
- **Attendance** - Student attendance tracking
- **Exam** - Examination management
- **Exam_Result** - Student examination results
- **Timetable** - Class scheduling

### Financial Entities
- **Fee_Structure** - Fee configuration by class
- **Payment** - Payment tracking and management

### Library Entities
- **Book** - Library book catalog
- **Book_Issue** - Book borrowing and return tracking

### System Entities
- **Role** - User role management
- **Message** - Communication system
- **Audit_Log** - System activity logging
- **Inventory** - Asset management

## 🎯 User Roles

### Admin
- Complete system access
- User management
- System configuration
- All module access

### Teacher
- Student management
- Attendance marking
- Result entry
- Class management

### Student
- Personal academic information
- Attendance viewing
- Result checking
- Fee information

### Parent
- Child's academic progress
- Attendance monitoring
- Fee payment tracking
- Communication with school

## 🔧 Technical Details

### Technology Stack
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript
- **UI Framework**: Bootstrap 5
- **Icons**: Font Awesome 6
- **Charts**: Chart.js

### Security Features
- Password hashing
- Role-based access control
- Session management
- SQL injection prevention
- XSS protection

### File Structure
```
MagodiSecondarySchool/
├── admin/                 # Admin portal
├── student/              # Student portal
├── staff/                # Staff portal
├── parent/               # Parent portal
├── config/               # Configuration files
├── includes/             # Common includes
├── database/             # Database schema
├── assets/               # Static assets
├── index.php             # Homepage
├── login.php             # Login page
├── setup.php             # Installation script
└── README.md             # This file
```

## 📱 Responsive Design

The system is fully responsive and works on:
- Desktop computers
- Tablets
- Mobile phones
- Various screen sizes

## 🎨 UI/UX Features

- Modern, clean interface
- Intuitive navigation
- Color-coded information
- Interactive dashboards
- Real-time data updates
- Print-friendly reports

## 📈 Reporting

The system provides comprehensive reports for:
- Student performance
- Attendance statistics
- Financial reports
- Library usage
- Staff performance
- Academic analytics

## 🔒 Security

- Secure authentication
- Role-based permissions
- Data encryption
- Audit logging
- Session management

## 📞 Support

For technical support or questions:
- Email: support@magodischool.mw
- Phone: +265 123 456 789
- Address: Area 23, Lilongwe, Malawi

## 📄 License

This project is proprietary software developed for Magodi Private School.

## 🏆 Credits

Developed for Magodi Private School
**Motto**: "Excellence Through Education"

---

**Version**: 1.0  
**Last Updated**: <?php echo date('Y-m-d'); ?>  
**Location**: Area 23, Lilongwe, Malawi
