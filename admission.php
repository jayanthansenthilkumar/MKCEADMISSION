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
            <h3>Confirmed Students</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Programme</th>
                            <th>Department</th>
                            <th>Batch</th>
                            <th>Mobile</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody id="studentsTableBody">
                        <tr>
                            <td colspan="7" class="text-center">Loading...</td>
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

<script src="assets/js/main.js"></script>
</body>
</html>
