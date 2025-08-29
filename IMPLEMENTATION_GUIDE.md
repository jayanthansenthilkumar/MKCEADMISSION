# MKCE Admission Portal - Single File Implementation

## Project Structure

This implementation consolidates the MKCE Admission Portal into a minimal set of files for better maintainability and organization.

### Core Files

1. **index.php** - Login page with embedded CSS and JavaScript
2. **admission.php** - Main dashboard with embedded CSS and JavaScript  
3. **api.php** - Single backend file handling all API requests
4. **config.php** - Database configuration
5. **logout.php** - Simple logout handler

### Features

#### Login System (index.php)
- Clean, responsive login interface
- Modern gradient design
- Form validation
- AJAX-based authentication
- Auto-redirect to dashboard

#### Dashboard (admission.php)
- Complete admission management system
- Responsive sidebar navigation
- Real-time statistics
- Student management
- Reports and analytics
- Modal-based forms
- All CSS and JavaScript embedded

#### Backend API (api.php)
- Single endpoint for all operations
- Action-based routing
- Session management
- Data validation
- Export functionality
- Database operations

### Supported Operations

#### Authentication
- `login` - Faculty login authentication

#### Admission Management  
- `save_admission` - Create new admission records
- `get_admissions` - Retrieve admission data with search
- `confirm_student` - Confirm student admission
- `reject_admission` - Reject admission application

#### Student Management
- `get_students` - Retrieve student data with search
- `get_student_details` - Get specific student information
- `save_student_details` - Update student information

#### Analytics & Reports
- `get_dashboard_stats` - Dashboard statistics
- `export_data` - Export data as CSV
- `health_check` - System health status

### Database Requirements

The system expects the following tables:
- `faculty` - Faculty login credentials
- `admission` - Student admission records  
- `ayear` - Academic year data

### Key Improvements

1. **Single File Architecture**: Reduced from multiple API files to one
2. **Embedded Assets**: CSS and JavaScript included in HTML files
3. **Modern UI**: Updated with better responsive design
4. **Enhanced Security**: Improved input validation and session handling
5. **Better UX**: Loading states, animations, and notifications

### Usage

1. Configure database settings in `config.php`
2. Import the SQL schema (`krconnect (1).sql`)
3. Access via web browser at your local server URL
4. Login with faculty credentials
5. Use the dashboard to manage admissions

### Browser Support

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Mobile responsive design
- Touch-friendly interface

### Dependencies

- PHP 7.4+ with mysqli extension
- MySQL/MariaDB database
- jQuery 3.7.0 (CDN)
- SweetAlert2 (CDN)
- Font Awesome 6.4.0 (CDN)
- Inter font (Google Fonts)

### File Sizes

- `index.php`: ~14KB (login page)
- `admission.php`: ~95KB (dashboard with embedded assets)
- `api.php`: ~17KB (complete backend)
- `config.php`: ~436B (database config)
- `logout.php`: ~88B (logout handler)

Total core system: ~126KB

### Development Notes

- All functionality from the original multi-file system preserved
- Improved error handling and user feedback
- Enhanced mobile responsiveness
- Modern CSS Grid and Flexbox layouts
- ES6+ JavaScript features
- AJAX-based data operations

### Security Features

- Session-based authentication
- SQL injection prevention
- XSS protection through proper escaping
- CSRF token support ready
- Input validation and sanitization

This consolidated implementation provides the same functionality as the original system while being more maintainable and easier to deploy.
