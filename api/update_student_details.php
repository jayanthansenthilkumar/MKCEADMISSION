<?php
header('Content-Type: application/json');
include '../config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $admission_id = $_POST['admission_id'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        $dob = $_POST['dob'] ?? null;
        $gender = $_POST['gender'] ?? null;
        $address = $_POST['address'] ?? null;
        $pincode = $_POST['pincode'] ?? null;
        $fname_father = $_POST['fname_father'] ?? null;
        $fname_mother = $_POST['fname_mother'] ?? null;
        $mobile_guardian = $_POST['mobile_guardian'] ?? null;
        
        // Get admission details
        $admission_sql = "SELECT * FROM admission WHERE id = ?";
        $stmt = $conn->prepare($admission_sql);
        $stmt->bind_param('i', $admission_id);
        $stmt->execute();
        $admission = $stmt->get_result()->fetch_assoc();
        
        if(!$admission) {
            throw new Exception('Admission record not found');
        }
        
        // Update sbasic table
        $update_sql = "UPDATE sbasic SET 
                       mobile = ?, email = ?, dob = ?, gender = ?, 
                       address = ?, pincode = ?, fname_father = ?, 
                       fname_mother = ?, mobile_guardian = ?
                       WHERE sid = ?";
        
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param('ssssssssss', 
            $mobile, $email, $dob, $gender, $address, $pincode,
            $fname_father, $fname_mother, $mobile_guardian, $admission['sid']
        );
        
        if($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Student details updated successfully'
            ]);
        } else {
            throw new Exception('Failed to update student details');
        }
        
    } catch(Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>
