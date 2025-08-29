<?php
session_start();
header('Content-Type: application/json');

// Database configuration
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "krconnect";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Get the action from POST data or URL parameter
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// If no action specified, determine based on POST data
if (empty($action)) {
    if (isset($_POST['id']) && isset($_POST['pass'])) {
        $action = 'login';
    } elseif (isset($_POST['sid']) && isset($_POST['fname'])) {
        $action = 'save_admission';
    } elseif (isset($_POST['admission_id']) && isset($_POST['mobile'])) {
        $action = 'save_student_details';
    }
}

// Route to appropriate function
switch ($action) {
    case 'login':
        handleLogin($conn);
        break;
    case 'save_admission':
        handleSaveAdmission($conn);
        break;
    case 'get_admissions':
        handleGetAdmissions($conn);
        break;
    case 'get_students':
        handleGetStudents($conn);
        break;
    case 'get_dashboard_stats':
        handleGetDashboardStats($conn);
        break;
    case 'confirm_student':
        handleConfirmStudent($conn);
        break;
    case 'reject_admission':
        handleRejectAdmission($conn);
        break;
    case 'save_student_details':
        handleSaveStudentDetails($conn);
        break;
    case 'complete_student_profile':
        handleCompleteStudentProfile($conn);
        break;
    case 'get_student_details':
        handleGetStudentDetails($conn);
        break;
    case 'export_data':
        handleExportData($conn);
        break;
    case 'health_check':
        handleHealthCheck($conn);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action or missing parameters']);
        break;
}

$conn->close();

// Login function
function handleLogin($conn) {
    if (!isset($_POST['id'], $_POST['pass'])) {
        echo json_encode(['success' => false, 'message' => 'Please enter faculty ID and password']);
        return;
    }

    $id = $conn->real_escape_string($_POST['id']);
    $pass = $_POST['pass'];

    $sql = "SELECT * FROM faculty WHERE id='$id' AND pass='$pass'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['id'];
        echo json_encode(['success' => true, 'message' => 'Login successful']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid faculty ID or password']);
    }
}

// Save admission function
function handleSaveAdmission($conn) {
    if (!isset($_SESSION['id'])) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        return;
    }

    // Validate required fields
    $required_fields = ['sid', 'fname', 'programme', 'department', 'batch', 'doadmission'];
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            echo json_encode(['success' => false, 'message' => ucfirst($field) . ' is required']);
            return;
        }
    }

    // Sanitize inputs
    $sid = $conn->real_escape_string($_POST['sid']);
    $fname = $conn->real_escape_string($_POST['fname']);
    $lname = $conn->real_escape_string($_POST['lname'] ?? '');
    $dob = $conn->real_escape_string($_POST['dob'] ?? '');
    $gender = $conn->real_escape_string($_POST['gender'] ?? '');
    $mobile = $conn->real_escape_string($_POST['mobile'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $programme = $conn->real_escape_string($_POST['programme']);
    $department = $conn->real_escape_string($_POST['department']);
    $batch = $conn->real_escape_string($_POST['batch']);
    $doadmission = $conn->real_escape_string($_POST['doadmission']);
    $ayear_id = intval($_POST['ayear_id'] ?? 1);
    $admitted_by = $_SESSION['id'];

    // Additional fields for enhanced admission
    $application_number = 'APP' . date('Y') . sprintf('%06d', rand(1, 999999));
    $admission_category = $conn->real_escape_string($_POST['admission_category'] ?? 'General');
    $admission_type = $conn->real_escape_string($_POST['admission_type'] ?? 'Regular');
    $previous_education = $conn->real_escape_string($_POST['previous_education'] ?? '');
    $marks_percentage = floatval($_POST['marks_percentage'] ?? 0);

    // Check if SID already exists
    $check_sql = "SELECT id FROM admission WHERE sid = '$sid'";
    $check_result = $conn->query($check_sql);

    if ($check_result && $check_result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Student ID already exists']);
        return;
    }

    // Insert admission record with enhanced fields
    $sql = "INSERT INTO admission (
                application_number, sid, fname, lname, dob, gender, mobile, email, 
                programme, department, batch, doadmission, admission_category, admission_type,
                previous_education, marks_percentage, status, ayear_id, admitted_by, 
                admission_stage, created_at
            ) VALUES (
                '$application_number', '$sid', '$fname', '$lname', " . ($dob ? "'$dob'" : "NULL") . ", 
                '$gender', '$mobile', '$email', '$programme', '$department', '$batch', 
                '$doadmission', '$admission_category', '$admission_type', '$previous_education', 
                $marks_percentage, 'pending', $ayear_id, '$admitted_by', 'application_submitted', NOW()
            )";

    if ($conn->query($sql)) {
        echo json_encode([
            'success' => true, 
            'message' => 'Admission application submitted successfully',
            'application_number' => $application_number,
            'next_stage' => 'pending_review'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    }
}

// Get admissions function
function handleGetAdmissions($conn) {
    if (!isset($_SESSION['id'])) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        return;
    }

    $search = $_GET['search'] ?? '';
    $whereClause = '';

    if (!empty($search)) {
        $search = $conn->real_escape_string($search);
        $whereClause = "WHERE (sid LIKE '%$search%' OR fname LIKE '%$search%' OR lname LIKE '%$search%' OR department LIKE '%$search%' OR programme LIKE '%$search%')";
    }

    $sql = "SELECT * FROM admission $whereClause ORDER BY created_at DESC";
    $result = $conn->query($sql);

    $admissions = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $admissions[] = $row;
        }
    }

    echo json_encode(['success' => true, 'data' => $admissions]);
}

// Get students function
function handleGetStudents($conn) {
    if (!isset($_SESSION['id'])) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        return;
    }

    $search = $_GET['search'] ?? '';
    $sid = $_GET['sid'] ?? '';
    $whereClause = "WHERE status IN ('confirmed', 'admitted')";

    if (!empty($search)) {
        $search = $conn->real_escape_string($search);
        $whereClause .= " AND (sid LIKE '%$search%' OR fname LIKE '%$search%' OR lname LIKE '%$search%' OR department LIKE '%$search%' OR programme LIKE '%$search%')";
    }

    if (!empty($sid)) {
        $sid = $conn->real_escape_string($sid);
        $whereClause .= " AND sid = '$sid'";
    }

    $sql = "SELECT *, 
            CASE 
                WHEN mobile IS NOT NULL AND email IS NOT NULL THEN 'Complete'
                WHEN mobile IS NOT NULL OR email IS NOT NULL THEN 'Partial'
                ELSE 'Incomplete'
            END as profile_status
            FROM admission $whereClause ORDER BY created_at DESC";
    
    $result = $conn->query($sql);

    $students = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    }

    echo json_encode(['success' => true, 'data' => $students]);
}

// Get dashboard stats function
function handleGetDashboardStats($conn) {
    if (!isset($_SESSION['id'])) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        return;
    }

    // Get total applications
    $total_sql = "SELECT COUNT(*) as total FROM admission";
    $total_result = $conn->query($total_sql);
    $total_applications = $total_result ? $total_result->fetch_assoc()['total'] : 0;

    // Get pending review
    $pending_sql = "SELECT COUNT(*) as pending FROM admission WHERE status = 'pending'";
    $pending_result = $conn->query($pending_sql);
    $pending_review = $pending_result ? $pending_result->fetch_assoc()['pending'] : 0;

    // Get confirmed students
    $confirmed_sql = "SELECT COUNT(*) as confirmed FROM admission WHERE status = 'confirmed'";
    $confirmed_result = $conn->query($confirmed_sql);
    $confirmed_students = $confirmed_result ? $confirmed_result->fetch_assoc()['confirmed'] : 0;

    // Get total students (confirmed + admitted)
    $students_sql = "SELECT COUNT(*) as students FROM admission WHERE status IN ('confirmed', 'admitted')";
    $students_result = $conn->query($students_sql);
    $total_students = $students_result ? $students_result->fetch_assoc()['students'] : 0;

    // Get department breakdown
    $dept_sql = "SELECT department, COUNT(*) as count FROM admission WHERE status IN ('confirmed', 'admitted') GROUP BY department ORDER BY count DESC LIMIT 5";
    $dept_result = $conn->query($dept_sql);
    $departments = [];
    
    if ($dept_result && $dept_result->num_rows > 0) {
        while ($row = $dept_result->fetch_assoc()) {
            $departments[] = $row;
        }
    }

    // Get recent activity
    $activity_sql = "SELECT * FROM admission ORDER BY created_at DESC LIMIT 5";
    $activity_result = $conn->query($activity_sql);
    $recent_activity = [];
    
    if ($activity_result && $activity_result->num_rows > 0) {
        while ($row = $activity_result->fetch_assoc()) {
            $recent_activity[] = [
                'type' => 'admission',
                'icon' => 'user-plus',
                'message' => 'New admission: ' . $row['fname'] . ' ' . $row['lname'] . ' (' . $row['sid'] . ')',
                'date' => $row['created_at']
            ];
        }
    }

    $stats = [
        'total_applications' => $total_applications,
        'pending_review' => $pending_review,
        'confirmed_students' => $confirmed_students,
        'total_students' => $total_students,
        'departments' => $departments,
        'recent_activity' => $recent_activity
    ];

    echo json_encode(['success' => true, 'data' => $stats]);
}

// Confirm student function
function handleConfirmStudent($conn) {
    if (!isset($_SESSION['id'])) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        return;
    }

    if (!isset($_POST['admission_id'])) {
        echo json_encode(['success' => false, 'message' => 'Admission ID is required']);
        return;
    }

    $admission_id = intval($_POST['admission_id']);
    $confirmed_by = $_SESSION['id'];

    // Update admission status to confirmed and change stage
    $sql = "UPDATE admission SET 
                status = 'confirmed', 
                confirmed_by = '$confirmed_by', 
                confirmed_at = NOW(),
                admission_stage = 'confirmed_pending_details'
            WHERE id = $admission_id";

    if ($conn->query($sql)) {
        echo json_encode([
            'success' => true, 
            'message' => 'Student confirmed successfully. Now collect complete details.',
            'next_stage' => 'collect_details'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    }
}

// Complete student profile function
function handleCompleteStudentProfile($conn) {
    if (!isset($_SESSION['id'])) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        return;
    }

    if (!isset($_POST['admission_id'])) {
        echo json_encode(['success' => false, 'message' => 'Admission ID is required']);
        return;
    }

    $admission_id = intval($_POST['admission_id']);
    
    // Sanitize all inputs
    $mobile = $conn->real_escape_string($_POST['mobile'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $dob = $conn->real_escape_string($_POST['dob'] ?? '');
    $gender = $conn->real_escape_string($_POST['gender'] ?? '');
    $blood_group = $conn->real_escape_string($_POST['blood_group'] ?? '');
    $religion = $conn->real_escape_string($_POST['religion'] ?? '');
    $caste = $conn->real_escape_string($_POST['caste'] ?? '');
    $nationality = $conn->real_escape_string($_POST['nationality'] ?? 'Indian');
    
    // Address information
    $address = $conn->real_escape_string($_POST['address'] ?? '');
    $city = $conn->real_escape_string($_POST['city'] ?? '');
    $state = $conn->real_escape_string($_POST['state'] ?? '');
    $pincode = $conn->real_escape_string($_POST['pincode'] ?? '');
    $country = $conn->real_escape_string($_POST['country'] ?? 'India');
    
    // Guardian information
    $father_name = $conn->real_escape_string($_POST['father_name'] ?? '');
    $mother_name = $conn->real_escape_string($_POST['mother_name'] ?? '');
    $guardian_mobile = $conn->real_escape_string($_POST['guardian_mobile'] ?? '');
    $guardian_email = $conn->real_escape_string($_POST['guardian_email'] ?? '');
    $guardian_occupation = $conn->real_escape_string($_POST['guardian_occupation'] ?? '');
    $annual_income = floatval($_POST['annual_income'] ?? 0);
    
    // Academic background
    $tenth_board = $conn->real_escape_string($_POST['tenth_board'] ?? '');
    $tenth_year = intval($_POST['tenth_year'] ?? 0);
    $tenth_percentage = floatval($_POST['tenth_percentage'] ?? 0);
    $twelfth_board = $conn->real_escape_string($_POST['twelfth_board'] ?? '');
    $twelfth_year = intval($_POST['twelfth_year'] ?? 0);
    $twelfth_percentage = floatval($_POST['twelfth_percentage'] ?? 0);
    $entrance_exam = $conn->real_escape_string($_POST['entrance_exam'] ?? '');
    $entrance_score = floatval($_POST['entrance_score'] ?? 0);
    
    // Emergency contact
    $emergency_contact_name = $conn->real_escape_string($_POST['emergency_contact_name'] ?? '');
    $emergency_contact_relation = $conn->real_escape_string($_POST['emergency_contact_relation'] ?? '');
    $emergency_contact_mobile = $conn->real_escape_string($_POST['emergency_contact_mobile'] ?? '');
    
    // Medical information
    $medical_conditions = $conn->real_escape_string($_POST['medical_conditions'] ?? '');
    $allergies = $conn->real_escape_string($_POST['allergies'] ?? '');
    
    // Validate required fields
    $required_fields = ['mobile', 'email', 'father_name', 'address', 'pincode'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            echo json_encode(['success' => false, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required']);
            return;
        }
    }

    // Update admission record with complete information
    $sql = "UPDATE admission SET 
                mobile = '$mobile',
                email = '$email',
                dob = " . ($dob ? "'$dob'" : "NULL") . ",
                gender = '$gender',
                blood_group = '$blood_group',
                religion = '$religion',
                caste = '$caste',
                nationality = '$nationality',
                address = '$address',
                city = '$city',
                state = '$state',
                pincode = '$pincode',
                country = '$country',
                father_name = '$father_name',
                mother_name = '$mother_name',
                guardian_mobile = '$guardian_mobile',
                guardian_email = '$guardian_email',
                guardian_occupation = '$guardian_occupation',
                annual_income = $annual_income,
                tenth_board = '$tenth_board',
                tenth_year = $tenth_year,
                tenth_percentage = $tenth_percentage,
                twelfth_board = '$twelfth_board',
                twelfth_year = $twelfth_year,
                twelfth_percentage = $twelfth_percentage,
                entrance_exam = '$entrance_exam',
                entrance_score = $entrance_score,
                emergency_contact_name = '$emergency_contact_name',
                emergency_contact_relation = '$emergency_contact_relation',
                emergency_contact_mobile = '$emergency_contact_mobile',
                medical_conditions = '$medical_conditions',
                allergies = '$allergies',
                admission_stage = 'profile_completed',
                profile_completed_at = NOW(),
                updated_at = NOW()
            WHERE id = $admission_id";

    if ($conn->query($sql)) {
        echo json_encode([
            'success' => true, 
            'message' => 'Student profile completed successfully',
            'stage' => 'profile_completed'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    }
}

// Reject admission function
function handleRejectAdmission($conn) {
    if (!isset($_SESSION['id'])) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        return;
    }

    if (!isset($_POST['admission_id'])) {
        echo json_encode(['success' => false, 'message' => 'Admission ID is required']);
        return;
    }

    $admission_id = intval($_POST['admission_id']);
    $rejected_by = $_SESSION['id'];

    $sql = "UPDATE admission SET status = 'rejected', rejected_by = '$rejected_by', rejected_at = NOW() WHERE id = $admission_id";

    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Admission rejected successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    }
}

// Save student details function
function handleSaveStudentDetails($conn) {
    if (!isset($_SESSION['id'])) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        return;
    }

    if (!isset($_POST['admission_id'])) {
        echo json_encode(['success' => false, 'message' => 'Admission ID is required']);
        return;
    }

    $admission_id = intval($_POST['admission_id']);
    $mobile = $conn->real_escape_string($_POST['mobile'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $dob = $conn->real_escape_string($_POST['dob'] ?? '');
    $gender = $conn->real_escape_string($_POST['gender'] ?? '');
    $address = $conn->real_escape_string($_POST['address'] ?? '');
    $pincode = $conn->real_escape_string($_POST['pincode'] ?? '');
    $fname_father = $conn->real_escape_string($_POST['fname_father'] ?? '');
    $fname_mother = $conn->real_escape_string($_POST['fname_mother'] ?? '');
    $mobile_guardian = $conn->real_escape_string($_POST['mobile_guardian'] ?? '');

    $sql = "UPDATE admission SET 
            mobile = '$mobile',
            email = '$email',
            dob = " . ($dob ? "'$dob'" : "NULL") . ",
            gender = '$gender',
            address = '$address',
            pincode = '$pincode',
            fname_father = '$fname_father',
            fname_mother = '$fname_mother',
            mobile_guardian = '$mobile_guardian',
            updated_at = NOW()
            WHERE id = $admission_id";

    if ($conn->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Student details updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    }
}

// Get student details function
function handleGetStudentDetails($conn) {
    if (!isset($_SESSION['id'])) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        return;
    }

    $admission_id = $_GET['admission_id'] ?? '';
    $sid = $_GET['sid'] ?? '';

    if (empty($admission_id) && empty($sid)) {
        echo json_encode(['success' => false, 'message' => 'Admission ID or Student ID is required']);
        return;
    }

    $whereClause = '';
    if (!empty($admission_id)) {
        $admission_id = intval($admission_id);
        $whereClause = "WHERE id = $admission_id";
    } else {
        $sid = $conn->real_escape_string($sid);
        $whereClause = "WHERE sid = '$sid'";
    }

    $sql = "SELECT * FROM admission $whereClause";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $student = $result->fetch_assoc();
        echo json_encode(['success' => true, 'data' => $student]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Student not found']);
    }
}

// Export data function
function handleExportData($conn) {
    if (!isset($_SESSION['id'])) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        return;
    }

    $type = $_GET['type'] ?? 'all';
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="mkce_' . $type . '_' . date('Y-m-d') . '.csv"');

    $output = fopen('php://output', 'w');

    switch ($type) {
        case 'admissions':
            fputcsv($output, ['Student ID', 'First Name', 'Last Name', 'Programme', 'Department', 'Batch', 'Admission Date', 'Status']);
            $sql = "SELECT sid, fname, lname, programme, department, batch, doadmission, status FROM admission ORDER BY created_at DESC";
            break;
        case 'students':
            fputcsv($output, ['Student ID', 'First Name', 'Last Name', 'Mobile', 'Email', 'Programme', 'Department', 'Batch', 'Status']);
            $sql = "SELECT sid, fname, lname, mobile, email, programme, department, batch, status FROM admission WHERE status IN ('confirmed', 'admitted') ORDER BY created_at DESC";
            break;
        default:
            fputcsv($output, ['Student ID', 'First Name', 'Last Name', 'Mobile', 'Email', 'Programme', 'Department', 'Batch', 'Admission Date', 'Status']);
            $sql = "SELECT sid, fname, lname, mobile, email, programme, department, batch, doadmission, status FROM admission ORDER BY created_at DESC";
    }

    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_array(MYSQLI_NUM)) {
            fputcsv($output, $row);
        }
    }

    fclose($output);
    exit;
}

// Health check function
function handleHealthCheck($conn) {
    $status = [
        'status' => 'healthy',
        'database' => 'connected',
        'timestamp' => date('Y-m-d H:i:s'),
        'version' => '1.0.0'
    ];

    // Test database connection
    $test_sql = "SELECT 1";
    $test_result = $conn->query($test_sql);
    
    if (!$test_result) {
        $status['status'] = 'unhealthy';
        $status['database'] = 'disconnected';
    }

    echo json_encode(['success' => true, 'data' => $status]);
}
?>
