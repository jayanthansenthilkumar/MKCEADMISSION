<?php
header('Content-Type: application/json');
include '../config.php';

try {
    $search = $_GET['search'] ?? '';
    $where_clause = '';
    
    if(!empty($search)) {
        $search = mysqli_real_escape_string($conn, $search);
        $where_clause = "WHERE s.sid LIKE '%$search%' OR s.fname LIKE '%$search%' OR s.lname LIKE '%$search%' 
                        OR a.department LIKE '%$search%' OR a.programme LIKE '%$search%'";
    }
    
    $sql = "SELECT s.*, a.programme, a.department, a.batch, a.doadmission,
                   CASE 
                       WHEN s.mobile IS NOT NULL AND s.email IS NOT NULL THEN 'Complete'
                       WHEN s.mobile IS NOT NULL OR s.email IS NOT NULL THEN 'Partial'
                       ELSE 'Incomplete'
                   END as profile_status
            FROM sbasic s 
            JOIN admission a ON s.sid = a.sid 
            $where_clause
            ORDER BY s.sid";
    
    $result = $conn->query($sql);
    $students = [];
    
    while($row = $result->fetch_assoc()) {
        $students[] = [
            'sid' => $row['sid'],
            'fname' => $row['fname'],
            'lname' => $row['lname'],
            'mobile' => $row['mobile'] ?? '-',
            'email' => $row['email'] ?? '-',
            'programme' => $row['programme'],
            'department' => $row['department'],
            'batch' => $row['batch'],
            'profile_status' => $row['profile_status'],
            'admission_date' => $row['doadmission']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'data' => $students,
        'total' => count($students)
    ]);
    
} catch(Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching students: ' . $e->getMessage()
    ]);
}
?>
