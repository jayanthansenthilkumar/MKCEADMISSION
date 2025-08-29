<?php
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="admissions_export_' . date('Y-m-d_H-i-s') . '.csv"');

include '../config.php';

try {
    $type = $_GET['type'] ?? 'admissions';
    
    if($type === 'admissions') {
        // Export admissions data
        $sql = "SELECT sid, fname, lname, programme, department, batch, doadmission 
                FROM admission 
                ORDER BY doadmission DESC";
        
        $result = $conn->query($sql);
        
        // Output CSV headers
        echo "Student ID,First Name,Last Name,Programme,Department,Batch,Admission Date\n";
        
        // Output data
        while($row = $result->fetch_assoc()) {
            echo '"' . $row['sid'] . '","' . $row['fname'] . '","' . $row['lname'] . '","' . 
                 $row['programme'] . '","' . $row['department'] . '","' . $row['batch'] . '","' . 
                 $row['doadmission'] . '"' . "\n";
        }
        
    } elseif($type === 'students') {
        // Export students data
        $sql = "SELECT s.sid, s.fname, s.lname, s.mobile, s.email, a.programme, a.department, a.batch 
                FROM sbasic s 
                JOIN admission a ON s.sid = a.sid 
                ORDER BY s.sid";
        
        $result = $conn->query($sql);
        
        // Output CSV headers
        echo "Student ID,First Name,Last Name,Mobile,Email,Programme,Department,Batch\n";
        
        // Output data
        while($row = $result->fetch_assoc()) {
            echo '"' . $row['sid'] . '","' . $row['fname'] . '","' . $row['lname'] . '","' . 
                 $row['mobile'] . '","' . $row['email'] . '","' . $row['programme'] . '","' . 
                 $row['department'] . '","' . $row['batch'] . '"' . "\n";
        }
    }
    
} catch(Exception $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}
?>
