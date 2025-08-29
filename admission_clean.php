<?php
session_start();
if(!isset($_SESSION['username']) && !isset($_SESSION['id'])){
    header("Location: index.php");
    exit;
}

// Use id if username is not set
$display_name = isset($_SESSION['username']) ? $_SESSION['username'] : $_SESSION['id'];

// Get faculty info
include 'config.php';
$faculty_id = $_SESSION['id'];
$faculty_sql = "SELECT name, dept FROM faculty WHERE id = '$faculty_id'";
$faculty_result = $conn->query($faculty_sql);
$faculty_info = $faculty_result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MKCE Admission Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="dashboard-layout">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
                <span>MKCE</span>
            </div>
            <button class="sidebar-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <nav class="sidebar-nav">
            <ul class="nav-menu">
                <li class="nav-item active">
                    <a href="#" class="nav-link" data-tab="dashboard-tab">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-tab="new-admission-tab">
                        <i class="fas fa-user-plus"></i>
                        <span>New Admission</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-tab="admissions-tab">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Manage Admissions</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-tab="students-tab">
                        <i class="fas fa-users"></i>
                        <span>Students Database</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-tab="reports-tab">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reports & Analytics</span>
                    </a>
                </li>
            </ul>
        </nav>
        
        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-info">
                    <div class="user-name"><?php echo $faculty_info['name']; ?></div>
                    <div class="user-role"><?php echo $faculty_info['dept']; ?></div>
                </div>
            </div>
            <button class="logout-btn" onclick="confirmLogout()">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <button class="menu-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h1>Admission Management System</h1>
            </div>
            <div class="header-right">
                <div class="header-actions">
                    <button class="notification-btn" onclick="showNotifications()">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    <div class="user-dropdown">
                        <button class="user-btn" onclick="toggleUserDropdown()">
                            <div class="user-avatar-small">
                                <?php echo strtoupper(substr($faculty_info['name'], 0, 1)); ?>
                            </div>
                            <span><?php echo $faculty_info['name']; ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <div class="content-area">
            <!-- Dashboard Tab -->
            <div id="dashboard-tab" class="tab-content active">
                <!-- Welcome Card -->
                <div class="welcome-card">
                    <div class="welcome-content">
                        <div class="welcome-text">
                            <h2>Welcome back, <?php echo $faculty_info['name']; ?>!</h2>
                            <p>Here's what's happening with admissions today</p>
                            <div class="welcome-date">
                                <i class="fas fa-calendar-alt"></i>
                                <span><?php echo date('l, F d, Y'); ?></span>
                            </div>
                        </div>
                        <div class="welcome-illustration">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="dashboard-stats">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon primary">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="stat-value" data-stat="total_applications" id="totalApplications">0</div>
                        <div class="stat-label">Total Applications</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+12% from last month</span>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon warning">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <div class="stat-value" data-stat="pending_review" id="pendingReview">0</div>
                        <div class="stat-label">Pending Review</div>
                        <div class="stat-change negative">
                            <i class="fas fa-arrow-down"></i>
                            <span>-5% from last week</span>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="stat-value" data-stat="confirmed_students" id="confirmedStudents">0</div>
                        <div class="stat-label">Confirmed Students</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+8% from last month</span>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon info">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                        </div>
                        <div class="stat-value" data-stat="total_students" id="totalStudents">0</div>
                        <div class="stat-label">Total Students</div>
                        <div class="stat-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>Active students</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <h3 class="section-title">
                        <i class="fas fa-bolt"></i>
                        Quick Actions
                    </h3>
                    <div class="actions-grid">
                        <div class="action-card" data-action="new-admission">
                            <div class="action-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h3>Add New Admission</h3>
                            <p>Register a new student admission</p>
                        </div>
                        <div class="action-card" data-action="confirmed-students">
                            <div class="action-icon">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            <h3>Review Admissions</h3>
                            <p>Review pending applications</p>
                        </div>
                        <div class="action-card" data-action="export-data">
                            <div class="action-icon">
                                <i class="fas fa-download"></i>
                            </div>
                            <h3>Export Data</h3>
                            <p>Download admission reports</p>
                        </div>
                        <div class="action-card" data-action="system-settings">
                            <div class="action-icon">
                                <i class="fas fa-cog"></i>
                            </div>
                            <h3>Settings</h3>
                            <p>Configure system settings</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="recent-activity">
                    <h3 class="section-title">
                        <i class="fas fa-clock"></i>
                        Recent Activity
                    </h3>
                    <div class="content-section">
                        <div class="section-body">
                            <div class="activity-list" id="recentActivityList">
                                <div class="activity-item">
                                    <div class="activity-icon confirmed">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="activity-content">
                                        <p><strong>System Ready:</strong> Dashboard loaded successfully</p>
                                        <span class="activity-time">Just now</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Admission Tab -->
            <div id="new-admission-tab" class="tab-content">
                <div class="content-section">
                    <div class="section-header">
                        <h3>
                            <i class="fas fa-user-plus"></i>
                            New Student Admission
                        </h3>
                    </div>
                    <div class="section-body">
                        <form id="admissionForm" class="admission-form">
                            <!-- Personal Information Section -->
                            <div class="form-section">
                                <h4 class="form-section-title">
                                    <i class="fas fa-user"></i>
                                    Personal Information
                                </h4>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="sid">Student ID *</label>
                                        <input type="text" id="sid" name="sid" class="form-control" required 
                                               placeholder="e.g., 25MKCECS001">
                                    </div>
                                    <div class="form-group">
                                        <label for="fname">First Name *</label>
                                        <input type="text" id="fname" name="fname" class="form-control" required 
                                               placeholder="Enter first name">
                                    </div>
                                    <div class="form-group">
                                        <label for="lname">Last Name</label>
                                        <input type="text" id="lname" name="lname" class="form-control" 
                                               placeholder="Enter last name">
                                    </div>
                                    <div class="form-group">
                                        <label for="dob">Date of Birth</label>
                                        <input type="date" id="dob" name="dob" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <select id="gender" name="gender" class="form-control">
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="mobile">Mobile Number</label>
                                        <input type="tel" id="mobile" name="mobile" class="form-control" 
                                               placeholder="+91 9876543210">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input type="email" id="email" name="email" class="form-control" 
                                               placeholder="student@example.com">
                                    </div>
                                </div>
                            </div>

                            <!-- Academic Information Section -->
                            <div class="form-section">
                                <h4 class="form-section-title">
                                    <i class="fas fa-graduation-cap"></i>
                                    Academic Information
                                </h4>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="programme">Programme *</label>
                                        <select id="programme" name="programme" class="form-control" required>
                                            <option value="">Select Programme</option>
                                            <option value="B.E">Bachelor of Engineering (B.E)</option>
                                            <option value="B.Tech">Bachelor of Technology (B.Tech)</option>
                                            <option value="M.E">Master of Engineering (M.E)</option>
                                            <option value="M.Tech">Master of Technology (M.Tech)</option>
                                            <option value="MBA">Master of Business Administration (MBA)</option>
                                            <option value="MCA">Master of Computer Applications (MCA)</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="department">Department *</label>
                                        <select id="department" name="department" class="form-control" required>
                                            <option value="">Select Department</option>
                                            <option value="Computer Science and Engineering">Computer Science and Engineering</option>
                                            <option value="Electronics and Communication Engineering">Electronics and Communication Engineering</option>
                                            <option value="Electrical and Electronics Engineering">Electrical and Electronics Engineering</option>
                                            <option value="Mechanical Engineering">Mechanical Engineering</option>
                                            <option value="Civil Engineering">Civil Engineering</option>
                                            <option value="Information Technology">Information Technology</option>
                                            <option value="Aeronautical Engineering">Aeronautical Engineering</option>
                                            <option value="Automobile Engineering">Automobile Engineering</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="batch">Batch *</label>
                                        <select id="batch" name="batch" class="form-control" required>
                                            <option value="">Select Batch</option>
                                            <option value="2025-2029">2025-2029</option>
                                            <option value="2026-2030">2026-2030</option>
                                            <option value="2027-2031">2027-2031</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="doadmission">Date of Admission *</label>
                                        <input type="date" id="doadmission" name="doadmission" class="form-control" required
                                               value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="ayear_id">Academic Year</label>
                                        <select id="ayear_id" name="ayear_id" class="form-control">
                                            <option value="">Select Academic Year</option>
                                            <?php
                                            $ayear_sql = "SELECT * FROM ayear ORDER BY id DESC";
                                            $ayear_result = $conn->query($ayear_sql);
                                            if($ayear_result) {
                                                while($row = $ayear_result->fetch_assoc()) {
                                                    echo "<option value='".$row['id']."'>".$row['ayear']."</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="form-actions">
                                <button type="button" class="btn btn-secondary" onclick="resetAdmissionForm()">
                                    <i class="fas fa-undo"></i>
                                    Reset Form
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    Save Admission
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Admissions Management Tab -->
            <div id="admissions-tab" class="tab-content">
                <div class="content-section">
                    <div class="section-header">
                        <h3>
                            <i class="fas fa-clipboard-list"></i>
                            Manage Admissions
                            <span class="badge" id="admissionsBadge">0</span>
                        </h3>
                        <div class="header-actions">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" id="admissionSearch" placeholder="Search admissions..." class="form-control">
                            </div>
                            <button class="btn btn-secondary" onclick="refreshAdmissions()">
                                <i class="fas fa-sync-alt"></i>
                                Refresh
                            </button>
                            <button class="btn btn-primary" onclick="exportData('admissions')">
                                <i class="fas fa-download"></i>
                                Export
                            </button>
                        </div>
                    </div>
                    <div class="section-body">
                        <!-- Admissions Table -->
                        <div class="table-responsive">
                            <table class="data-table" id="admissionsTable">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Student Name</th>
                                        <th>Programme</th>
                                        <th>Department</th>
                                        <th>Batch</th>
                                        <th>Admission Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students Database Tab -->
            <div id="students-tab" class="tab-content">
                <div class="content-section">
                    <div class="section-header">
                        <h3>
                            <i class="fas fa-users"></i>
                            Students Database
                            <span class="badge" id="studentsBadge">0</span>
                        </h3>
                        <div class="header-actions">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" id="studentSearch" placeholder="Search students..." class="form-control">
                            </div>
                            <button class="btn btn-secondary" onclick="refreshStudents()">
                                <i class="fas fa-sync-alt"></i>
                                Refresh
                            </button>
                            <button class="btn btn-primary" onclick="exportData('students')">
                                <i class="fas fa-download"></i>
                                Export
                            </button>
                        </div>
                    </div>
                    <div class="section-body">
                        <!-- Students Table -->
                        <div class="table-responsive">
                            <table class="data-table" id="studentsTable">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Student Name</th>
                                        <th>Programme</th>
                                        <th>Department</th>
                                        <th>Batch</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Profile Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reports & Analytics Tab -->
            <div id="reports-tab" class="tab-content">
                <div class="content-section">
                    <div class="section-header">
                        <h3>
                            <i class="fas fa-chart-bar"></i>
                            Reports & Analytics
                        </h3>
                        <div class="header-actions">
                            <select id="reportPeriod" class="form-control">
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month" selected>This Month</option>
                                <option value="year">This Year</option>
                            </select>
                            <button class="btn btn-primary" onclick="generateReport()">
                                <i class="fas fa-chart-line"></i>
                                Generate Report
                            </button>
                        </div>
                    </div>
                    <div class="section-body">
                        <!-- Analytics Overview -->
                        <div class="analytics-overview">
                            <div class="analytics-card">
                                <div class="analytics-header">
                                    <h4>Admission Summary</h4>
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                                <div class="analytics-content">
                                    <div class="summary-item">
                                        <span class="summary-label">Total Applications:</span>
                                        <span class="summary-value" id="reportTotalApplications">0</span>
                                    </div>
                                    <div class="summary-item">
                                        <span class="summary-label">Confirmed Students:</span>
                                        <span class="summary-value" id="reportConfirmedStudents">0</span>
                                    </div>
                                    <div class="summary-item">
                                        <span class="summary-label">Rejection Rate:</span>
                                        <span class="summary-value" id="reportRejectionRate">0%</span>
                                    </div>
                                    <div class="summary-item">
                                        <span class="summary-label">Popular Department:</span>
                                        <span class="summary-value" id="reportPopularDept">-</span>
                                    </div>
                                </div>
                            </div>

                            <div class="analytics-card">
                                <div class="analytics-header">
                                    <h4>Department Breakdown</h4>
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="analytics-content">
                                    <div class="department-stats" id="departmentStats">
                                        <!-- Department statistics will be loaded here -->
                                    </div>
                                </div>
                            </div>

                            <div class="analytics-card">
                                <div class="analytics-header">
                                    <h4>Recent Trends</h4>
                                    <i class="fas fa-trend-up"></i>
                                </div>
                                <div class="analytics-content">
                                    <div class="trend-item">
                                        <span class="trend-label">This Month:</span>
                                        <span class="trend-value positive">+15%</span>
                                    </div>
                                    <div class="trend-item">
                                        <span class="trend-label">This Week:</span>
                                        <span class="trend-value positive">+8%</span>
                                    </div>
                                    <div class="trend-item">
                                        <span class="trend-label">Today:</span>
                                        <span class="trend-value neutral">0%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Export Options -->
                        <div class="export-section">
                            <h4 class="section-title">
                                <i class="fas fa-download"></i>
                                Export Reports
                            </h4>
                            <div class="export-options">
                                <button class="btn btn-primary" onclick="exportData('admissions')">
                                    <i class="fas fa-file-csv"></i>
                                    Export Admissions
                                </button>
                                <button class="btn btn-success" onclick="exportData('students')">
                                    <i class="fas fa-file-excel"></i>
                                    Export Students
                                </button>
                                <button class="btn btn-info" onclick="exportData('all')">
                                    <i class="fas fa-file-archive"></i>
                                    Export All Data
                                </button>
                                <button class="btn btn-warning" onclick="generatePDFReport()">
                                    <i class="fas fa-file-pdf"></i>
                                    PDF Report
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Student Profile Modal -->
<div id="studentProfileModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3>
                <i class="fas fa-user"></i>
                Student Profile
            </h3>
            <button type="button" class="modal-close" onclick="closeStudentProfileModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="profile-sections" id="studentProfileContent">
                <!-- Profile content will be loaded here -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeStudentProfileModal()">
                Close
            </button>
            <button type="button" class="btn btn-primary" onclick="editStudentProfile()">
                <i class="fas fa-edit"></i>
                Edit Profile
            </button>
        </div>
    </div>
</div>

<!-- Student Details Modal -->
<div id="studentDetailsModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3>
                <i class="fas fa-user-plus"></i>
                Complete Student Details
            </h3>
            <button type="button" class="modal-close" onclick="closeStudentDetailsModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="studentDetailsForm">
                <input type="hidden" id="student_admission_id" name="admission_id">
                
                <!-- Personal Information Section -->
                <div class="form-section">
                    <h4 class="form-section-title">
                        <i class="fas fa-user"></i>
                        Personal Information
                    </h4>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="student_sid">Student ID</label>
                            <input type="text" id="student_sid" name="sid" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="student_fname">First Name</label>
                            <input type="text" id="student_fname" name="fname" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="student_lname">Last Name</label>
                            <input type="text" id="student_lname" name="lname" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="student_dob">Date of Birth</label>
                            <input type="date" id="student_dob" name="dob" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="student_gender">Gender</label>
                            <select id="student_gender" name="gender" class="form-control">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="student_mobile">Mobile Number *</label>
                            <input type="tel" id="student_mobile" name="mobile" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="student_email">Email Address *</label>
                            <input type="email" id="student_email" name="email" class="form-control" required>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="form-section">
                    <h4 class="form-section-title">
                        <i class="fas fa-map-marker-alt"></i>
                        Address Information
                    </h4>
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="student_address">Complete Address</label>
                            <textarea id="student_address" name="address" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="student_pincode">PIN Code</label>
                            <input type="text" id="student_pincode" name="pincode" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Guardian Information -->
                <div class="form-section">
                    <h4 class="form-section-title">
                        <i class="fas fa-users"></i>
                        Guardian Information
                    </h4>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="student_fname_father">Father's Name</label>
                            <input type="text" id="student_fname_father" name="fname_father" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="student_fname_mother">Mother's Name</label>
                            <input type="text" id="student_fname_mother" name="fname_mother" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="student_mobile_guardian">Guardian Mobile</label>
                            <input type="tel" id="student_mobile_guardian" name="mobile_guardian" class="form-control">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeStudentDetailsModal()">
                Cancel
            </button>
            <button type="button" class="btn btn-primary" onclick="saveStudentDetails()">
                <i class="fas fa-save"></i>
                Save Details
            </button>
        </div>
    </div>
</div>

<script src="assets/js/main.js"></script>
</body>
</html>
