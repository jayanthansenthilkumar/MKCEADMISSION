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
                    <button class="notification-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    <div class="user-dropdown">
                        <button class="user-btn">
                            <div class="user-avatar-small">
                                <i class="fas fa-user"></i>
                            </div>
                            <span><?php echo $display_name; ?></span>
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
                                <p>Approve or reject pending admissions</p>
                            </div>
                        </div>
                        <div class="action-card" onclick="switchTab('students-tab')">
                            <div class="action-icon">
                                <i class="fas fa-database"></i>
                            </div>
                            <div class="action-text">
                                <h4>Student Database</h4>
                                <p>View and manage confirmed students</p>
                            </div>
                        </div>
                        <div class="action-card" onclick="exportStudentsData()">
                            <div class="action-icon">
                                <i class="fas fa-download"></i>
                            </div>
                            <div class="action-text">
                                <h4>Export Data</h4>
                                <p>Download student records</p>
                            </div>
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
                                <!-- Activity items will be loaded here -->
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
                                               placeholder="e.g., 24MKCECS001">
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
                                            <option value="2024-2028">2024-2028</option>
                                            <option value="2025-2029">2025-2029</option>
                                            <option value="2026-2030">2026-2030</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="doadmission">Date of Admission *</label>
                                        <input type="date" id="doadmission" name="doadmission" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="ayear_id">Academic Year</label>
                                        <select id="ayear_id" name="ayear_id" class="form-control">
                                            <option value="">Select Academic Year</option>
                                            <!-- Will be populated via AJAX -->
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Address Information Section -->
                            <div class="form-section">
                                <h4 class="form-section-title">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Address Information
                                </h4>
                                <div class="form-grid">
                                    <div class="form-group full-width">
                                        <label for="address">Complete Address</label>
                                        <textarea id="address" name="address" class="form-control" rows="3" 
                                                placeholder="Enter complete address"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" id="city" name="city" class="form-control" 
                                               placeholder="Enter city">
                                    </div>
                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <input type="text" id="state" name="state" class="form-control" 
                                               placeholder="Enter state">
                                    </div>
                                    <div class="form-group">
                                        <label for="pincode">PIN Code</label>
                                        <input type="text" id="pincode" name="pincode" class="form-control" 
                                               placeholder="Enter PIN code">
                                    </div>
                                </div>
                            </div>

                            <!-- Guardian Information Section -->
                            <div class="form-section">
                                <h4 class="form-section-title">
                                    <i class="fas fa-users"></i>
                                    Guardian Information
                                </h4>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="fname_father">Father's Name</label>
                                        <input type="text" id="fname_father" name="fname_father" class="form-control" 
                                               placeholder="Enter father's name">
                                    </div>
                                    <div class="form-group">
                                        <label for="fname_mother">Mother's Name</label>
                                        <input type="text" id="fname_mother" name="fname_mother" class="form-control" 
                                               placeholder="Enter mother's name">
                                    </div>
                                    <div class="form-group">
                                        <label for="mobile_guardian">Guardian Mobile</label>
                                        <input type="tel" id="mobile_guardian" name="mobile_guardian" class="form-control" 
                                               placeholder="+91 9876543210">
                                    </div>
                                    <div class="form-group">
                                        <label for="guardian_relation">Guardian Relation</label>
                                        <select id="guardian_relation" name="guardian_relation" class="form-control">
                                            <option value="">Select Relation</option>
                                            <option value="Father">Father</option>
                                            <option value="Mother">Mother</option>
                                            <option value="Guardian">Guardian</option>
                                            <option value="Other">Other</option>
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
                        <!-- Filter Options -->
                        <div class="filter-section">
                            <div class="filter-group">
                                <label>Filter by Status:</label>
                                <select id="statusFilter" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="ADMITTED">Admitted</option>
                                    <option value="CONFIRMED">Confirmed</option>
                                    <option value="REJECTED">Rejected</option>
                                </select>
                            </div>
                            <div class="filter-group">
                                <label>Filter by Department:</label>
                                <select id="deptFilter" class="form-control">
                                    <option value="">All Departments</option>
                                    <option value="Computer Science and Engineering">CSE</option>
                                    <option value="Electronics and Communication Engineering">ECE</option>
                                    <option value="Electrical and Electronics Engineering">EEE</option>
                                    <option value="Mechanical Engineering">MECH</option>
                                    <option value="Civil Engineering">CIVIL</option>
                                    <option value="Information Technology">IT</option>
                                </select>
                            </div>
                            <div class="filter-group">
                                <label>Filter by Programme:</label>
                                <select id="programmeFilter" class="form-control">
                                    <option value="">All Programmes</option>
                                    <option value="B.E">B.E</option>
                                    <option value="B.Tech">B.Tech</option>
                                    <option value="M.E">M.E</option>
                                    <option value="M.Tech">M.Tech</option>
                                    <option value="MCA">MCA</option>
                                    <option value="MBA">MBA</option>
                                </select>
                            </div>
                            <button class="btn btn-outline" onclick="clearFilters()">
                                <i class="fas fa-times"></i>
                                Clear Filters
                            </button>
                        </div>

                        <!-- Admissions Table -->
                        <div class="table-responsive">
                            <table class="data-table" id="admissionsTable">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAllAdmissions" onchange="toggleSelectAll('admissions')">
                                        </th>
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

                        <!-- Bulk Actions -->
                        <div class="bulk-actions" id="bulkActionsAdmissions" style="display: none;">
                            <div class="bulk-info">
                                <span id="selectedCountAdmissions">0</span> items selected
                            </div>
                            <div class="bulk-buttons">
                                <button class="btn btn-success" onclick="bulkConfirmStudents()">
                                    <i class="fas fa-check"></i>
                                    Confirm Selected
                                </button>
                                <button class="btn btn-danger" onclick="bulkRejectAdmissions()">
                                    <i class="fas fa-times"></i>
                                    Reject Selected
                                </button>
                                <button class="btn btn-secondary" onclick="clearSelection('admissions')">
                                    <i class="fas fa-times-circle"></i>
                                    Clear Selection
                                </button>
                            </div>
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
                        <!-- Student Statistics -->
                        <div class="mini-stats">
                            <div class="mini-stat-card">
                                <div class="mini-stat-icon">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <div class="mini-stat-details">
                                    <div class="mini-stat-number" id="totalStudentsCount">0</div>
                                    <div class="mini-stat-label">Total Students</div>
                                </div>
                            </div>
                            <div class="mini-stat-card">
                                <div class="mini-stat-icon">
                                    <i class="fas fa-male"></i>
                                </div>
                                <div class="mini-stat-details">
                                    <div class="mini-stat-number" id="maleStudentsCount">0</div>
                                    <div class="mini-stat-label">Male Students</div>
                                </div>
                            </div>
                            <div class="mini-stat-card">
                                <div class="mini-stat-icon">
                                    <i class="fas fa-female"></i>
                                </div>
                                <div class="mini-stat-details">
                                    <div class="mini-stat-number" id="femaleStudentsCount">0</div>
                                    <div class="mini-stat-label">Female Students</div>
                                </div>
                            </div>
                            <div class="mini-stat-card">
                                <div class="mini-stat-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="mini-stat-details">
                                    <div class="mini-stat-number" id="completeProfilesCount">0</div>
                                    <div class="mini-stat-label">Complete Profiles</div>
                                </div>
                            </div>
                        </div>

                        <!-- Students Table -->
                        <div class="table-responsive">
                            <table class="data-table" id="studentsTable">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAllStudents" onchange="toggleSelectAll('students')">
                                        </th>
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

                        <!-- Bulk Actions for Students -->
                        <div class="bulk-actions" id="bulkActionsStudents" style="display: none;">
                            <div class="bulk-info">
                                <span id="selectedCountStudents">0</span> students selected
                            </div>
                            <div class="bulk-buttons">
                                <button class="btn btn-primary" onclick="bulkUpdateProfiles()">
                                    <i class="fas fa-edit"></i>
                                    Update Selected
                                </button>
                                <button class="btn btn-warning" onclick="bulkSendNotifications()">
                                    <i class="fas fa-bell"></i>
                                    Send Notifications
                                </button>
                                <button class="btn btn-secondary" onclick="clearSelection('students')">
                                    <i class="fas fa-times-circle"></i>
                                    Clear Selection
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
    <!-- New Admission Tab -->
    <div id="new-admission-tab" class="tab-content active">
        <div class="card">
            <h3>Add New Admission</h3>
            <form id="admissionForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="sid">Student ID (SID)</label>
                        <input type="text" id="sid" name="sid" required placeholder="e.g., 26MKCEAL001">
                    </div>
                    <div class="form-group">
                        <label for="fname">First Name</label>
                        <input type="text" id="fname" name="fname" required>
                    </div>
                    <div class="form-group">
                        <label for="lname">Last Name</label>
                        <input type="text" id="lname" name="lname">
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="programme">Programme</label>
                        <select id="programme" name="programme" required>
                            <option value="">Select Programme</option>
                            <option value="B.E">B.E (Bachelor of Engineering)</option>
                            <option value="B.Tech">B.Tech (Bachelor of Technology)</option>
                            <option value="M.E">M.E (Master of Engineering)</option>
                            <option value="M.Tech">M.Tech (Master of Technology)</option>
                            <option value="MCA">MCA (Master of Computer Applications)</option>
                            <option value="MBA">MBA (Master of Business Administration)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select id="department" name="department" required>
                            <option value="">Select Department</option>
                            <option value="Computer Science and Engineering">Computer Science and Engineering</option>
                            <option value="Electronics and Communication Engineering">Electronics and Communication Engineering</option>
                            <option value="Electrical and Electronics Engineering">Electrical and Electronics Engineering</option>
                            <option value="Mechanical Engineering">Mechanical Engineering</option>
                            <option value="Civil Engineering">Civil Engineering</option>
                            <option value="Information Technology">Information Technology</option>
                            <option value="Artificial Intelligence and Data Science">Artificial Intelligence and Data Science</option>
                            <option value="Computer Science and Business Systems">Computer Science and Business Systems</option>
                            <option value="Artificial Intelligence and Machine Learning">Artificial Intelligence and Machine Learning</option>
                            <option value="Master of Computer Applications">Master of Computer Applications</option>
                            <option value="Master of Business Administration">Master of Business Administration</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="batch">Batch</label>
                        <select id="batch" name="batch" required>
                            <option value="">Select Batch</option>
                            <option value="2024-2028">2024-2028</option>
                            <option value="2025-2029">2025-2029</option>
                            <option value="2026-2030">2026-2030</option>
                            <option value="2027-2031">2027-2031</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="doadmission">Date of Admission</label>
                        <input type="date" id="doadmission" name="doadmission" required value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="admcate">Admission Category</label>
                        <select id="admcate" name="admcate" required>
                            <option value="">Select Category</option>
                            <option value="General">General</option>
                            <option value="OBC">OBC</option>
                            <option value="SC">SC</option>
                            <option value="ST">ST</option>
                            <option value="EWS">EWS</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="admtype">Admission Type</label>
                        <select id="admtype" name="admtype" required>
                            <option value="">Select Type</option>
                            <option value="Regular">Regular</option>
                            <option value="Management">Management</option>
                            <option value="NRI">NRI</option>
                            <option value="Lateral Entry">Lateral Entry</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="initial_payment">Initial Payment (₹)</label>
                        <input type="number" id="initial_payment" name="initial_payment" min="0" step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="ayear_id">Academic Year</label>
                        <select id="ayear_id" name="ayear_id" required>
                            <option value="">Select Academic Year</option>
                            <?php
                            $ayear_sql = "SELECT * FROM ayear ORDER BY id DESC";
                            $ayear_result = $conn->query($ayear_sql);
                            while($row = $ayear_result->fetch_assoc()) {
                                echo "<option value='".$row['id']."'>".$row['ayear']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="text-center mt-20">
                    <button type="submit" class="btn btn-primary">Save Admission Record</button>
                    <button type="reset" class="btn btn-secondary">Reset Form</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Admissions List Tab -->
    <div id="admissions-tab" class="tab-content">
        <div class="card">
            <h3>Manage Admission Records</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Programme</th>
                            <th>Department</th>
                            <th>Batch</th>
                            <th>Admission Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="admissionsTableBody">
                        <tr>
                            <td colspan="8" class="text-center">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Students List Tab -->
    <div id="students-tab" class="tab-content">
        <div class="card">
            <div class="card-header">
                <h3>Confirmed Students Database</h3>
                <div class="card-actions">
                    <button class="btn btn-primary" onclick="openStudentDetailsModal()">
                        <i class="icon-plus"></i> Add Student Details
                    </button>
                    <button class="btn btn-secondary" onclick="exportStudentsData()">
                        <i class="icon-download"></i> Export Data
                    </button>
                </div>
            </div>
            <div class="search-filter-container">
                <div class="search-box">
                    <input type="text" id="studentSearch" placeholder="Search students...">
                </div>
                <div class="filter-controls">
                    <select id="departmentFilter">
                        <option value="">All Departments</option>
                        <option value="Computer Science and Engineering">CSE</option>
                        <option value="Electronics and Communication Engineering">ECE</option>
                        <option value="Electrical and Electronics Engineering">EEE</option>
                        <option value="Mechanical Engineering">ME</option>
                        <option value="Civil Engineering">CE</option>
                        <option value="Information Technology">IT</option>
                        <option value="Artificial Intelligence and Data Science">AIDS</option>
                        <option value="Computer Science and Business Systems">CSBS</option>
                        <option value="Artificial Intelligence and Machine Learning">AIML</option>
                    </select>
                    <select id="batchFilter">
                        <option value="">All Batches</option>
                        <option value="2024-2028">2024-2028</option>
                        <option value="2025-2029">2025-2029</option>
                        <option value="2026-2030">2026-2030</option>
                        <option value="2027-2031">2027-2031</option>
                    </select>
                </div>
            </div>
            <div class="table-container">
                <table class="students-table">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Programme</th>
                            <th>Department</th>
                            <th>Batch</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="studentsTableBody">
                        <tr>
                            <td colspan="9" class="text-center">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Reports Tab -->
    <div id="reports-tab" class="tab-content">
        <div class="card">
            <h3>Reports & Analytics</h3>
            <p class="text-center">Reports functionality will be implemented here.</p>
        </div>
    </div>

        </div>
    </main>
</div>

<!-- Student Details Modal -->
<div id="studentDetailsModal" class="modal">
    <div class="modal-content large-modal">
        <div class="modal-header">
            <h3>Complete Student Information</h3>
            <span class="close" onclick="closeStudentModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form id="studentDetailsForm">
                <input type="hidden" id="student_sid" name="sid">
                
                <!-- Personal Information Section -->
                <div class="form-section">
                    <h4>Personal Information</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="modal_fname">First Name *</label>
                            <input type="text" id="modal_fname" name="fname" required>
                        </div>
                        <div class="form-group">
                            <label for="modal_lname">Last Name</label>
                            <input type="text" id="modal_lname" name="lname">
                        </div>
                        <div class="form-group">
                            <label for="modal_gender">Gender *</label>
                            <select id="modal_gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" id="dob" name="dob">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="blood">Blood Group</label>
                            <select id="blood" name="blood">
                                <option value="">Select Blood Group</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="religion">Religion</label>
                            <input type="text" id="religion" name="religion">
                        </div>
                        <div class="form-group">
                            <label for="caste">Caste</label>
                            <input type="text" id="caste" name="caste">
                        </div>
                        <div class="form-group">
                            <label for="nationality">Nationality</label>
                            <input type="text" id="nationality" name="nationality" value="Indian">
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="form-section">
                    <h4>Contact Information</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="mobile">Mobile Number *</label>
                            <input type="tel" id="mobile" name="mobile" required>
                        </div>
                        <div class="form-group">
                            <label for="pmobile">Parent Mobile</label>
                            <input type="tel" id="pmobile" name="pmobile">
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="offemail">Official Email</label>
                            <input type="email" id="offemail" name="offemail">
                        </div>
                    </div>
                </div>

                <!-- Address Information Section -->
                <div class="form-section">
                    <h4>Address Information</h4>
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="paddress">Permanent Address</label>
                            <textarea id="paddress" name="paddress" rows="3"></textarea>
                        </div>
                        <div class="form-group full-width">
                            <label for="taddress">Temporary Address</label>
                            <textarea id="taddress" name="taddress" rows="3"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city">
                        </div>
                        <div class="form-group">
                            <label for="state">State</label>
                            <input type="text" id="state" name="state">
                        </div>
                        <div class="form-group">
                            <label for="zip">ZIP Code</label>
                            <input type="text" id="zip" name="zip">
                        </div>
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" id="country" name="country" value="India">
                        </div>
                    </div>
                </div>

                <!-- Academic Information Section -->
                <div class="form-section">
                    <h4>Academic Information</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="modal_programme">Programme</label>
                            <input type="text" id="modal_programme" name="programme" readonly>
                        </div>
                        <div class="form-group">
                            <label for="modal_department">Department</label>
                            <input type="text" id="modal_department" name="department" readonly>
                        </div>
                        <div class="form-group">
                            <label for="modal_batch">Batch</label>
                            <input type="text" id="modal_batch" name="batch" readonly>
                        </div>
                        <div class="form-group">
                            <label for="cutoff">Cutoff Mark</label>
                            <input type="number" id="cutoff" name="cutoff" min="0" max="200" step="0.01">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstgra">First Graduate</label>
                            <select id="firstgra" name="firstgra">
                                <option value="">Select</option>
                                <option value="YES">Yes</option>
                                <option value="NO">No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exam_status">Exam Status</label>
                            <input type="text" id="exam_status" name="exam_status">
                        </div>
                        <div class="form-group">
                            <label for="exam_mark">Exam Mark</label>
                            <input type="text" id="exam_mark" name="exam_mark">
                        </div>
                        <div class="form-group">
                            <label for="languages">Languages Known</label>
                            <input type="text" id="languages" name="languages" placeholder="e.g., Tamil, English, Hindi">
                        </div>
                    </div>
                </div>

                <!-- Hostel Information Section -->
                <div class="form-section">
                    <h4>Accommodation Information</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="hosday">Accommodation Type</label>
                            <select id="hosday" name="hosday">
                                <option value="">Select Type</option>
                                <option value="Hosteller">Hosteller</option>
                                <option value="Dayscholar">Day Scholar</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="hosname">Hostel Name</label>
                            <input type="text" id="hosname" name="hosname">
                        </div>
                        <div class="form-group">
                            <label for="room">Room Number</label>
                            <input type="text" id="room" name="room">
                        </div>
                        <div class="form-group">
                            <label for="busno">Bus Number</label>
                            <input type="number" id="busno" name="busno">
                        </div>
                    </div>
                </div>

                <!-- Guardian Information Section -->
                <div class="form-section">
                    <h4>Guardian Information</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="guarname">Guardian Name</label>
                            <input type="text" id="guarname" name="guarname">
                        </div>
                        <div class="form-group">
                            <label for="guarmobile">Guardian Mobile</label>
                            <input type="tel" id="guarmobile" name="guarmobile">
                        </div>
                        <div class="form-group full-width">
                            <label for="guaraddress">Guardian Address</label>
                            <textarea id="guaraddress" name="guaraddress" rows="2"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Documents Section -->
                <div class="form-section">
                    <h4>Identity Documents</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="aadhar">Aadhar Number</label>
                            <input type="text" id="aadhar" name="aadhar" maxlength="12">
                        </div>
                        <div class="form-group">
                            <label for="pan">PAN Number</label>
                            <input type="text" id="pan" name="pan" maxlength="10">
                        </div>
                        <div class="form-group">
                            <label for="saadhar">Student Aadhar</label>
                            <input type="text" id="saadhar" name="saadhar" maxlength="12">
                        </div>
                        <div class="form-group">
                            <label for="span">Student PAN</label>
                            <input type="text" id="span" name="span" maxlength="10">
                        </div>
                    </div>
                </div>

                <!-- SWOT Analysis Section -->
                <div class="form-section">
                    <h4>SWOT Analysis</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="Strengths">Strengths</label>
                            <textarea id="Strengths" name="Strengths" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="Weaknesses">Weaknesses</label>
                            <textarea id="Weaknesses" name="Weaknesses" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="Opportunities">Opportunities</label>
                            <textarea id="Opportunities" name="Opportunities" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="Threats">Threats</label>
                            <textarea id="Threats" name="Threats" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeStudentModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Student Details</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="assets/js/main.js"></script>
</body>
</html>
