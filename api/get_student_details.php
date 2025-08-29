<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['id'])){
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

include '../config.php';

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['sid'])) {
    
    $sid = mysqli_real_escape_string($conn, $_GET['sid']);
    
    // Get student details from sbasic table
    $sql = "SELECT s.*, ay.ayear, a.admission_id, a.status as admission_status
            FROM sbasic s 
            LEFT JOIN ayear ay ON s.ayear_id = ay.id 
            LEFT JOIN admission a ON s.admission_id = a.admission_id
            WHERE s.sid = '$sid'";
    
    $result = $conn->query($sql);
    
    if($result && $result->num_rows > 0) {
        $student = $result->fetch_assoc();
        echo json_encode(['status' => 'success', 'data' => $student]);
    } else {
        // If not found in sbasic, check admission table
        $admission_sql = "SELECT a.*, ay.ayear 
                         FROM admission a 
                         LEFT JOIN ayear ay ON a.ayear_id = ay.id 
                         WHERE a.sid = '$sid' AND a.status = 'CONFIRMED'";
        
        $admission_result = $conn->query($admission_sql);
        
        if($admission_result && $admission_result->num_rows > 0) {
            $admission_data = $admission_result->fetch_assoc();
            // Convert admission data to student format
            $student_data = [
                'sid' => $admission_data['sid'],
                'fname' => $admission_data['fname'],
                'lname' => $admission_data['lname'],
                'gender' => $admission_data['gender'],
                'programme' => $admission_data['programme'],
                'department' => $admission_data['department'],
                'batch' => $admission_data['batch'],
                'doadmission' => $admission_data['doadmission'],
                'admcate' => $admission_data['admcate'],
                'admtype' => $admission_data['admtype'],
                'admission_id' => $admission_data['admission_id'],
                'ayear_id' => $admission_data['ayear_id'],
                'ayear' => $admission_data['ayear']
            ];
            echo json_encode(['status' => 'success', 'data' => $student_data]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Student not found']);
        }
    }
    
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

$conn->close();
?>
