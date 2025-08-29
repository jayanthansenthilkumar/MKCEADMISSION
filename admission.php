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
    <title>Admission Portal - MKCE</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="dashboard-container">
    <div class="header">
        <div>
            <h1>MKCE Admission Portal</h1>
            <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 14px;">
                Welcome, <?php echo $faculty_info['name']; ?> - <?php echo $faculty_info['dept']; ?>
            </p>
        </div>
        <div class="header-actions">
            <div class="user-info">
                <?php echo $display_name; ?>
            </div>
            <button class="logout-btn" onclick="confirmLogout()">Logout</button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card admissions">
            <div class="stat-number">0</div>
            <div class="stat-label">Total Admissions</div>
        </div>
        <div class="stat-card pending">
            <div class="stat-number">0</div>
            <div class="stat-label">Pending Review</div>
        </div>
        <div class="stat-card confirmed">
            <div class="stat-number">0</div>
            <div class="stat-label">Confirmed Students</div>
        </div>
        <div class="stat-card rejected">
            <div class="stat-number">0</div>
            <div class="stat-label">Rejected</div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="nav-tabs">
        <button class="nav-tab active" data-tab="new-admission-tab">New Admission</button>
        <button class="nav-tab" data-tab="admissions-tab">Manage Admissions</button>
        <button class="nav-tab" data-tab="students-tab">Students List</button>
        <button class="nav-tab" data-tab="reports-tab">Reports</button>
    </div>

    <!-- Tab Contents -->
    
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
