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
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Update admission status to CONFIRMED
        $update_sql = "UPDATE admission SET status = 'CONFIRMED' WHERE admission_id = $admission_id";
        
        if(!$conn->query($update_sql)) {
            throw new Exception("Failed to update admission status");
        }
        
        // The trigger will automatically create the student record in sbasic table
        // Check if the trigger worked by looking for the student record
        $check_sql = "SELECT sid FROM admission WHERE admission_id = $admission_id";
        $check_result = $conn->query($check_sql);
        
        if(!$check_result || $check_result->num_rows === 0) {
            throw new Exception("Admission record not found");
        }
        
        $admission_data = $check_result->fetch_assoc();
        $original_sid = $admission_data['sid'];
        
        // Generate the new SID (remove MKCE as per trigger logic)
        $new_sid = str_replace('MKCE', '', $original_sid);
        
        // Commit transaction
        $conn->commit();
        
        echo json_encode([
            'status' => 'success', 
            'message' => 'Student confirmed successfully',
            'new_sid' => $new_sid
        ]);
        
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

$conn->close();
?>
