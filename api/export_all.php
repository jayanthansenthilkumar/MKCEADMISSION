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
header('Content-Disposition: attachment; filename="all_data_export_' . date('Y-m-d') . '.csv"');

// Create file pointer to output stream
$output = fopen('php://output', 'w');

try {
    // First section: Admissions data
    fputcsv($output, ['=== ADMISSIONS DATA ===']);
    fputcsv($output, [
        'Admission ID',
        'Student ID', 
        'First Name',
        'Last Name',
        'Programme',
        'Department',
        'Batch',
        'Date of Admission',
        'Status',
        'Academic Year',
        'Created At'
    ]);
    
    // Get admission records
    $sql = "SELECT a.*, ay.ayear 
            FROM admission a 
            LEFT JOIN ayear ay ON a.ayear_id = ay.id 
            ORDER BY a.created_at DESC";
    
    $result = $conn->query($sql);
    
    if($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $row['admission_id'],
                $row['sid'],
                $row['fname'],
                $row['lname'] ?? '',
                $row['programme'],
                $row['department'],
                $row['batch'],
                $row['doadmission'],
                $row['status'],
                $row['ayear'] ?? '',
                $row['created_at']
            ]);
        }
    }
    
    // Add separator
    fputcsv($output, ['']);
    fputcsv($output, ['=== CONFIRMED STUDENTS DATA ===']);
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
    
    // Get student records from sbasic table
    $sql2 = "SELECT s.*, ay.ayear 
             FROM sbasic s 
             LEFT JOIN ayear ay ON s.ayear_id = ay.id 
             ORDER BY s.sid ASC";
    
    $result2 = $conn->query($sql2);
    
    if($result2 && $result2->num_rows > 0) {
        while($row = $result2->fetch_assoc()) {
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
