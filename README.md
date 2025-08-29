# MKCE Admission Portal

A comprehensive admission portal system for MKCE (Muthayammal King College of Engineering) built with PHP, MySQL, HTML, CSS, and JavaScript.

## Project Structure

```
AC/
├── assets/
│   ├── css/
│   │   └── style.css          # Main stylesheet
│   └── js/
│       └── main.js            # Main JavaScript file
├── api/
│   ├── save_admission.php     # Save new admission records
│   ├── get_stats.php          # Get dashboard statistics
│   ├── get_admissions.php     # Get all admission records
│   ├── get_students.php       # Get confirmed students
│   ├── confirm_student.php    # Confirm admission (moves to sbasic)
│   └── reject_admission.php   # Reject admission
├── index.php                  # Login page
├── admission.php              # Main dashboard
├── login.php                  # Login processing
├── logout.php                 # Logout processing
├── config.php                 # Database configuration
└── krconnect (1).sql         # Database schema
```

## Database Schema

The system uses the following main tables:

### 1. `admission` table
- Stores initial admission records
- Status: ADMITTED, PENDING, CONFIRMED, REJECTED
- Uses auto-generated SID (e.g., 26MKCEAL001)

### 2. `sbasic` table
- Stores confirmed student records
- Triggered automatically when admission status is set to CONFIRMED
- SID is converted (removes MKCE prefix)

### 3. `faculty` table
- Stores faculty login credentials and information
- Used for authentication

### 4. `ayear` table
- Academic year reference data

## Features

### Login System
- Faculty authentication using ID and password
- Session management
- Sweet Alert notifications

### Dashboard
- Statistics cards showing admission counts
- Tabbed interface for different functions
- Responsive design

### Admission Management
1. **New Admission**: Add new admission records
2. **Manage Admissions**: View and process pending admissions
3. **Students List**: View confirmed students
4. **Reports**: Placeholder for future reporting features

### Workflow
1. Faculty adds new admission record → Status: ADMITTED
2. Faculty can confirm the admission → Status: CONFIRMED → Student record created in sbasic
3. Faculty can reject the admission → Status: REJECTED

## Installation

1. Import the `krconnect (1).sql` file to create the database
2. Update database credentials in `config.php`
3. Place files in your web server directory
4. Access via web browser

## Default Login
Use any faculty ID and password from the `faculty` table in the database.

## Technical Features

- **Responsive Design**: Works on desktop and mobile
- **AJAX Operations**: Smooth user experience without page reloads
- **Database Triggers**: Automatic student record creation
- **Input Validation**: Both client-side and server-side validation
- **Security**: SQL injection protection, session management

## Future Enhancements

- Student photo upload
- Document management
- Fee payment tracking
- Email notifications
- Advanced reporting
- Bulk operations
- Export functionality

## Browser Support
- Chrome (recommended)
- Firefox
- Safari
- Edge

## Dependencies
- jQuery 3.7.0
- SweetAlert2
- Google Fonts (Inter)
