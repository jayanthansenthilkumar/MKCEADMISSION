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
header('Content-Disposition: attachment; filename="admissions_export_' . date('Y-m-d') . '.csv"');

// Create file pointer to output stream
$output = fopen('php://output', 'w');

// Add CSV headers
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

try {
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
    
} catch(Exception $e) {
    fputcsv($output, ['Error: ' . $e->getMessage()]);
}

fclose($output);
$conn->close();
?>
