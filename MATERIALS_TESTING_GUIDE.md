# Materials Management Testing Guide

## Overview
This guide will help you test the complete materials management functionality in the LMS.

## Prerequisites
- Database migrations completed
- Development server running (`php spark serve`)
- Test users created (admin, teacher, student)

## Testing Steps

### Step 1: Seed Test Data (if needed)

Run the seeder to create test users:
```bash
php spark db:seed RbacUserSeeder
```

This creates:
- **Admin**: admin@example.com / secret1234
- **Teacher**: teacher@example.com / secret1234
- **Student**: student@example.com / secret1234

### Step 2: Test as Admin/Teacher - Upload Materials

1. **Login as Admin or Teacher**
   - Navigate to: http://localhost:8080/login
   - Login with teacher@example.com / secret1234

2. **Access Upload Page**
   - Go to: http://localhost:8080/admin/course/1/upload
   - (Replace '1' with an actual course ID from your database)

3. **Upload a File**
   - Select a PDF, DOC, PPT, or other allowed file (max 10MB)
   - Click "Upload Material"
   - Verify success message appears
   - Check that file appears in "Existing Materials" section

4. **Verify File Storage**
   - Check: `writable/uploads/materials/` directory
   - Confirm file was saved with random name

5. **Test Validation**
   - Try uploading without selecting a file (should fail)
   - Try uploading a disallowed file type like .exe (should fail)
   - Try uploading a file larger than 10MB (should fail)

### Step 3: Test Material Listing

1. **View Course Materials**
   - Navigate to: http://localhost:8080/materials/list/1
   - Verify all uploaded materials are displayed
   - Check that file names and upload dates are correct

### Step 4: Test as Student - View and Download Materials

1. **Login as Student**
   - Logout and login with student@example.com / secret1234

2. **Enroll in Course** (if not already enrolled)
   - Navigate to student enrollments
   - Enroll in the course that has materials

3. **Access Materials**
   - Click "My Materials" in navigation
   - Or go to: http://localhost:8080/student/materials
   - Verify materials from enrolled courses are displayed

4. **Download Material**
   - Click "Download" button on any material
   - Verify file downloads with original filename
   - Verify file opens correctly

5. **Test Access Control**
   - Try accessing: http://localhost:8080/materials/download/1
   - If not enrolled, should see error message
   - Enroll in course, then download should work

### Step 5: Test Material Deletion

1. **Login as Admin or Teacher**
   - Navigate to course materials page

2. **Delete a Material**
   - Click delete (trash icon) on a material
   - Confirm deletion in popup
   - Verify material is removed from list
   - Verify file is deleted from filesystem

3. **Test Permissions**
   - Login as student
   - Try accessing delete URL directly
   - Should be denied access

### Step 6: Test Edge Cases

1. **Unenrolled Student Access**
   - Login as student
   - Unenroll from course
   - Try to download material from that course
   - Should see "Please enroll in the course first" error

2. **Non-existent Material**
   - Try accessing: http://localhost:8080/materials/download/99999
   - Should see "Material not found" error

3. **Teacher Permissions**
   - Login as teacher
   - Try to upload to another teacher's course
   - Should be denied (only admin or course teacher can upload)

## Expected Results

### ✅ Success Criteria

- [x] Admin can upload materials to any course
- [x] Teacher can upload materials to their own courses
- [x] Students can view materials from enrolled courses only
- [x] Students can download materials from enrolled courses
- [x] File validation works (type, size)
- [x] Files are stored securely in writable/uploads/materials/
- [x] Original filenames are preserved for download
- [x] Materials can be deleted by admin/teacher
- [x] Unauthorized access is blocked
- [x] Error messages are clear and helpful

## Database Verification

Check the materials table:
```sql
SELECT m.*, c.title as course_title 
FROM materials m 
JOIN courses c ON c.id = m.course_id 
ORDER BY m.created_at DESC;
```

## File System Verification

Check uploaded files:
```bash
dir writable\uploads\materials
```

## Troubleshooting

### Issue: Upload fails silently
- Check `writable/uploads/materials/` directory exists
- Check directory permissions (should be writable)
- Check PHP upload_max_filesize and post_max_size settings

### Issue: Download fails
- Verify file exists in filesystem
- Check file path in database matches actual file
- Verify user is enrolled in course

### Issue: Access denied errors
- Verify user is logged in
- Check user role (admin/teacher/student)
- For students, verify enrollment in course

## Routes Reference

```php
// Admin/Teacher routes
GET  /admin/course/{id}/upload  - Display upload form
POST /admin/course/{id}/upload  - Process file upload

// General routes (with auth)
GET  /materials/list/{course_id}     - List course materials
GET  /materials/delete/{id}          - Delete material
GET  /materials/download/{id}        - Download material

// Student routes
GET  /student/materials              - View all materials from enrolled courses
```

## Security Features Implemented

1. **Authentication Required** - All routes require login
2. **Role-Based Access** - Upload restricted to admin/teacher
3. **Enrollment Check** - Students must be enrolled to download
4. **File Type Validation** - Only allowed file types accepted
5. **File Size Limit** - Maximum 10MB per file
6. **Secure File Storage** - Files stored outside public directory
7. **Random Filenames** - Prevents filename conflicts and guessing
8. **Direct Access Prevention** - index.html in uploads directory

## Next Steps

After testing, you can:
1. Integrate materials into course detail pages
2. Add file preview functionality
3. Implement version control for materials
4. Add bulk upload feature
5. Create material categories/tags
6. Add download statistics/tracking
