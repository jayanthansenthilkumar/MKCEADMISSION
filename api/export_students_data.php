<?php
session_start();

if(!isset($_SESSION['id'])){
    http_response_code(401);
    echo "Unauthorized";
    exit;
}

include '../config.php';

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="students_export_' . date('Y-m-d') . '.csv"');

// Create file pointer to output stream
$output = fopen('php://output', 'w');

// Add CSV headers
fputcsv($output, [
    'Student ID',
    'First Name',
    'Last Name',
    'Programme',
    'Department',
    'Batch',
    'Date of Birth',
    'Gender',
    'Mobile',
    'Email',
    'Address',
    'Pincode',
    'Father Name',
    'Mother Name',
    'Guardian Mobile',
    'Academic Year',
    'Created At'
]);

try {
    // Get student records from sbasic table
    $sql = "SELECT s.*, ay.ayear 
            FROM sbasic s 
            LEFT JOIN ayear ay ON s.ayear_id = ay.id 
            ORDER BY s.sid ASC";
    
    $result = $conn->query($sql);
    
    if($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $row['sid'],
                $row['fname'],
                $row['lname'] ?? '',
                $row['programme'],
                $row['department'],
                $row['batch'],
                $row['dob'] ?? '',
                $row['gender'] ?? '',
                $row['mobile'] ?? '',
                $row['email'] ?? '',
                $row['address'] ?? '',
                $row['pincode'] ?? '',
                $row['fname_father'] ?? '',
                $row['fname_mother'] ?? '',
                $row['mobile_guardian'] ?? '',
                $row['ayear'] ?? '',
                $row['created_at'] ?? ''
            ]);
        }
    }
    
} catch(Exception $e) {
    fputcsv($output, ['Error: ' . $e->getMessage()]);
}

fclose($output);
$conn->close();
?>
