<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['id'])){
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

include '../config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Validate required fields
    $required_fields = ['sid', 'fname', 'programme', 'department', 'batch', 'doadmission', 'admcate', 'admtype', 'ayear_id'];
    
    foreach($required_fields as $field) {
        if(empty($_POST[$field])) {
            echo json_encode(['status'=>'error','message'=>"$field is required"]);
            exit;
        }
    }
    
    // Sanitize inputs
    $sid = $conn->real_escape_string($_POST['sid']);
    $fname = $conn->real_escape_string($_POST['fname']);
    $lname = $conn->real_escape_string($_POST['lname'] ?? '');
    $gender = $conn->real_escape_string($_POST['gender'] ?? '');
    $programme = $conn->real_escape_string($_POST['programme']);
    $department = $conn->real_escape_string($_POST['department']);
    $batch = $conn->real_escape_string($_POST['batch']);
    $doadmission = $conn->real_escape_string($_POST['doadmission']);
    $admcate = $conn->real_escape_string($_POST['admcate']);
    $admtype = $conn->real_escape_string($_POST['admtype']);
    $initial_payment = floatval($_POST['initial_payment'] ?? 0);
    $ayear_id = intval($_POST['ayear_id']);
    $admitted_by = $_SESSION['id'];
    
    // Check if SID already exists
    $check_sql = "SELECT admission_id FROM admission WHERE sid = '$sid'";
    $check_result = $conn->query($check_sql);
    
    if($check_result->num_rows > 0) {
        echo json_encode(['status'=>'error','message'=>'Student ID already exists']);
        exit;
    }
    
    // Insert admission record
    $sql = "INSERT INTO admission (sid, fname, lname, gender, programme, department, batch, doadmission, admcate, admtype, initial_payment, status, ayear_id, admitted_by) 
            VALUES ('$sid', '$fname', '$lname', '$gender', '$programme', '$department', '$batch', '$doadmission', '$admcate', '$admtype', $initial_payment, 'ADMITTED', $ayear_id, '$admitted_by')";
    
    if($conn->query($sql)) {
        echo json_encode(['status'=>'success','message'=>'Admission record saved successfully']);
    } else {
        echo json_encode(['status'=>'error','message'=>'Database error: ' . $conn->error]);
    }
    
} else {
    echo json_encode(['status'=>'error','message'=>'Invalid request method']);
}

$conn->close();
?>
