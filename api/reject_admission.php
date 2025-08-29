<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['id'])){
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

include '../config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admission_id'])) {
    
    $admission_id = intval($_POST['admission_id']);
    
    // Update admission status to REJECTED
    $update_sql = "UPDATE admission SET status = 'REJECTED' WHERE admission_id = $admission_id";
    
    if($conn->query($update_sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Admission rejected successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to reject admission']);
    }
    
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

$conn->close();
?>
