# 📄 Simple Document-Based Exam System

## Overview
A simple system where:
- **Teachers** upload exam documents (PDF, Word, etc.)
- **Students** download exams, answer them, and upload their answer sheets
- **Teachers** download submissions, grade them, and add scores/comments

---

## 🚀 Quick Setup (1 Click!)

**Run this link:**
```
http://localhost/MagodiSecondarySchool/setup_simple_exam_system.php
```

**What it creates:**
- ✅ `uploads/exams/` folder - for exam documents
- ✅ `uploads/submissions/` folder - for student answers
- ✅ `exam_documents` table - tracks uploaded exam files
- ✅ `student_submissions` table - tracks student submissions

---

## 📝 How It Works

### For Teachers:

1. **Create Exam**
   - Go to Staff > Exams
   - Click "Create New Examination"
   - Fill in exam details (name, subject, date, marks, etc.)

2. **Upload Exam Document**
   - Click "Upload Exam Document"
   - Choose file (PDF, Word, Image, etc.)
   - Add description
   - Students can now see it!

3. **View Submissions**
   - See which students have submitted
   - Download student answer sheets
   - Grade offline or online

4. **Add Grades**
   - Enter score and grade
   - Add comments/feedback
   - Student can see results

### For Students:

1. **View Exams**
   - Go to Student > My Exams
   - See available exams

2. **Download Exam**
   - Click "Download Exam"
   - Opens PDF/Word document
   - Read questions

3. **Answer Exam**
   - Write answers on paper
   - Or type in Word/PDF
   - Save your answer sheet

4. **Upload Answers**
   - Click "Submit Answers"
   - Choose your file (PDF, Word, Image, etc.)
   - Upload
   - Wait for teacher to grade

5. **View Results**
   - After grading, see your score
   - Read teacher comments
   - Download graded submission if available

---

## 📁 File Structure

```
uploads/
├── exams/           ← Teachers upload exam documents here
│   ├── exam_13_biology.pdf
│   ├── exam_16_agriculture.docx
│   └── exam_17_math.pdf
│
└── submissions/     ← Students upload answer sheets here
    ├── submission_13_1.pdf
    ├── submission_16_2.docx
    └── submission_17_3.jpg
```

---

## 💾 Database Tables

### exam_documents
Stores uploaded exam files:
```sql
- Document_ID
- Exam_ID (links to exam table)
- File_Name (saved name)
- Original_Name (original filename)
- File_Type (pdf, docx, jpg, etc.)
- File_Size
- Upload_Date
- Uploaded_By (teacher ID)
- Description
```

### student_submissions
Stores student answer uploads:
```sql
- Submission_ID
- Exam_ID
- Student_ID
- File_Name
- Original_Name
- File_Type
- File_Size
- Submission_Date
- Score
- Grade
- Teacher_Comments
- Status (Pending/Graded)
```

---

## 📤 Supported File Types

### For Exams:
- ✅ PDF (.pdf)
- ✅ Word (.doc, .docx)
- ✅ Excel (.xls, .xlsx)
- ✅ Images (.jpg, .png)
- ✅ Text (.txt)

### For Submissions:
- ✅ PDF (.pdf)
- ✅ Word (.doc, .docx)
- ✅ Images (.jpg, .png)
- ✅ Text (.txt)

---

## 🎯 User Flow Examples

### Example 1: Mathematics Test

**Teacher:**
1. Creates "Mathematics Test 1" exam
2. Uploads `math_test_1.pdf` (questions 1-10)
3. Sets due date: Oct 25, 2025
4. Sets total marks: 100

**Student (Chisomo):**
1. Logs in, sees "Mathematics Test 1"
2. Downloads `math_test_1.pdf`
3. Answers on paper, scans to `chisomo_math_answers.pdf`
4. Uploads submission
5. Status: "Pending"

**Teacher:**
1. Downloads `chisomo_math_answers.pdf`
2. Grades: 85/100
3. Adds comment: "Good work! Check question 5."
4. Saves grade

**Student (Chisomo):**
1. Sees score: 85/100, Grade: B
2. Reads teacher comment
3. Can download graded submission

### Example 2: Biology Assignment

**Teacher:**
1. Creates "Biology Assignment"
2. Uploads `biology_assignment.docx` (essay questions)
3. Sets due date: Oct 19, 2025

**Student:**
1. Downloads Word document
2. Types answers directly in Word
3. Saves as `my_biology_answers.docx`
4. Uploads submission

**Teacher:**
1. Downloads all submissions
2. Grades each one
3. Adds feedback

---

## ✨ Features

### Teachers Can:
- ✅ Upload multiple documents per exam
- ✅ Edit/delete documents
- ✅ See submission status (who submitted, who didn't)
- ✅ Download all submissions at once
- ✅ Grade online or offline
- ✅ Add detailed comments
- ✅ Re-grade if needed

### Students Can:
- ✅ View all available exams
- ✅ Download exam documents anytime
- ✅ Submit answers before deadline
- ✅ See submission status
- ✅ View grades and feedback
- ✅ Re-submit if allowed

### System Features:
- ✅ File size limits (10MB default)
- ✅ File type validation
- ✅ Virus scanning (optional)
- ✅ Automatic file naming
- ✅ Submission tracking
- ✅ Grade history

---

## 🔒 Security Features

1. **File Upload Security:**
   - File type whitelist
   - File size limits
   - Rename files to prevent overwriting
   - Store outside web root (optional)

2. **Access Control:**
   - Only teachers can upload exams
   - Only students in class can see exams
   - Students can only download their own submissions
   - Teachers can only see their class submissions

3. **Data Protection:**
   - Files stored with unique names
   - Original names preserved in database
   - Submission tracking with IP addresses
   - Automatic backups (optional)

---

## 📊 Admin Dashboard

Teachers can see:
- Total exams created
- Total submissions received
- Pending grading count
- Average scores
- Submission timeline

---

## 🛠️ Customization

### Change Upload Limits:
Edit `php.ini`:
```ini
upload_max_filesize = 10M
post_max_size = 10M
```

### Add More File Types:
Edit upload validation:
```php
$allowed = ['pdf', 'doc', 'docx', 'jpg', 'png', 'txt', 'zip'];
```

### Change Storage Location:
```php
$uploadDir = '/path/to/secure/location/';
```

---

## ❓ FAQ

**Q: Can students see other students' submissions?**  
A: No, students can only see their own submissions.

**Q: Can teachers download all submissions at once?**  
A: Yes, there's a "Download All" button.

**Q: What if a student submits the wrong file?**  
A: They can re-submit before the deadline (if enabled).

**Q: Can exams have multiple documents?**  
A: Yes, teachers can upload multiple files per exam.

**Q: What happens after the deadline?**  
A: Late submissions can be disabled or marked as late.

---

## 🎓 Benefits of This System

✅ **Simple** - Easy to use for everyone  
✅ **Familiar** - Works like email attachments  
✅ **Flexible** - Any file type  
✅ **Offline** - Students can work offline  
✅ **Proven** - Similar to Google Classroom  
✅ **No Training** - Everyone knows how to download/upload  

---

## 🚀 Get Started Now!

1. **Run Setup:**
   ```
   http://localhost/MagodiSecondarySchool/setup_simple_exam_system.php
   ```

2. **Login as Teacher:**
   ```
   http://localhost/MagodiSecondarySchool/simple_login.php
   ```

3. **Create Your First Exam!**

That's it! 🎉

