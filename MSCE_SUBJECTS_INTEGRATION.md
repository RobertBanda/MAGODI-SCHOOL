# MSCE Subjects Integration - Magodi Private School

## Overview
This document outlines the integration of Malawi School Certificate of Education (MSCE) subjects into the Magodi Private School Management System, based on the official Malawi National Examinations Board (MANEB) curriculum.

## Facebook Page Integration
- **Facebook Page**: [https://www.facebook.com/share/1CsgegFzDj/](https://www.facebook.com/share/1CsgegFzDj/)
- **Integration**: Social media links and Facebook SDK integration
- **Features**: Like buttons, share buttons, and page embedding

## MSCE Subject Structure

### Core Subjects (Mandatory)
These are compulsory subjects that all students must take:

1. **English** (Code: ENG)
   - English Language and Literature
   - Core communication skills

2. **Mathematics** (Code: MATH)
   - Core mathematical concepts
   - Problem-solving skills

3. **Chichewa** (Code: CHI)
   - National language
   - Cultural and linguistic development

4. **Biology** (Code: BIO)
   - Life sciences
   - Biological processes and systems

5. **Chemistry** (Code: CHEM)
   - Chemical sciences
   - Laboratory skills and theory

6. **Physics** (Code: PHY)
   - Physical sciences
   - Mathematical applications in science

### Elective Subjects (Choose 3-4)
Students select from these subjects based on their interests and career goals:

1. **Agriculture** (Code: AGR)
   - Agricultural science and practices
   - Food production and sustainability

2. **Geography** (Code: GEO)
   - Physical and human geography
   - Environmental studies

3. **History** (Code: HIST)
   - Historical analysis and research
   - Cultural and social development

4. **Bible Knowledge** (Code: BIBLE)
   - Religious studies
   - Moral and ethical development

5. **Business Studies** (Code: BUS)
   - Business principles and practices
   - Entrepreneurship and economics

### Additional Subjects
Supporting subjects for comprehensive education:

1. **Computer Studies** (Code: CS)
   - Information and Communication Technology
   - Digital literacy

2. **Physical Education** (Code: PE)
   - Sports and physical development
   - Health and wellness

3. **Life Skills** (Code: LS)
   - Personal development
   - Social and emotional learning

4. **Social Studies** (Code: SS)
   - Junior form social sciences
   - Civic education

## Database Implementation

### Subject Table Structure
```sql
CREATE TABLE Subject (
    Subject_ID INT PRIMARY KEY AUTO_INCREMENT,
    Subject_Name VARCHAR(100) NOT NULL,
    Description TEXT,
    Subject_Code VARCHAR(20) UNIQUE,
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Updated_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Subject Categories
- **Core**: MSCE mandatory subjects
- **Elective**: MSCE optional subjects
- **Additional**: Supporting subjects

## System Features

### Subject Management
- Add, edit, and delete subjects
- Categorize by MSCE type
- Subject code validation
- Curriculum alignment

### Class-Subject Assignment
- Assign subjects to classes
- Teacher-subject mapping
- Timetable integration
- Capacity management

### Examination System
- MSCE-aligned exam structure
- Subject-specific result tracking
- Grade calculation
- Performance analytics

## Integration Benefits

1. **Curriculum Compliance**
   - Aligned with MANEB requirements
   - Standardized subject codes
   - Official examination structure

2. **Academic Excellence**
   - Comprehensive subject coverage
   - Balanced curriculum
   - Career pathway support

3. **System Efficiency**
   - Automated subject management
   - Integrated reporting
   - Streamlined administration

4. **Parent Engagement**
   - Clear subject information
   - Progress tracking
   - Academic transparency

## Usage Instructions

### For Administrators
1. Access Subject Management from Admin Portal
2. View MSCE subject categories
3. Add custom subjects if needed
4. Assign subjects to classes
5. Monitor subject performance

### For Teachers
1. View assigned subjects
2. Track student progress
3. Enter examination results
4. Generate subject reports

### For Students
1. View enrolled subjects
2. Check subject results
3. Track academic progress
4. Access subject resources

### For Parents
1. Monitor child's subjects
2. View subject performance
3. Track academic development
4. Communicate with teachers

## Technical Implementation

### Files Modified
- `database/schema.sql` - Added MSCE subjects
- `admin/subjects.php` - Subject management interface
- `admin/api/subjects.php` - Subject API endpoints
- `includes/school_branding.php` - School branding integration
- `includes/facebook_integration.php` - Facebook integration

### Database Updates
- Added MSCE core subjects
- Added MSCE elective subjects
- Added additional subjects
- Updated school information with Facebook page

## Future Enhancements

1. **Advanced Analytics**
   - Subject performance trends
   - Comparative analysis
   - Predictive modeling

2. **Mobile Integration**
   - Mobile app support
   - Push notifications
   - Offline access

3. **External Integrations**
   - MANEB API integration
   - Government reporting
   - Third-party assessments

## Support and Maintenance

For technical support or questions about MSCE integration:
- Email: support@magodischool.mw
- Phone: +265 123 456 789
- Facebook: [https://www.facebook.com/share/1CsgegFzDj/](https://www.facebook.com/share/1CsgegFzDj/)

---

**Document Version**: 1.0  
**Last Updated**: <?php echo date('Y-m-d'); ?>  
**School**: Magodi Private School  
**Location**: Area 23, Lilongwe, Malawi
