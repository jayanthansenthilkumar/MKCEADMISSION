<?php
header('Content-Type: application/json');
include '../config.php';

try {
    // Get dashboard statistics
    $stats = [];
    
    // Total applications
    $total_sql = "SELECT COUNT(*) as count FROM admission";
    $total_result = $conn->query($total_sql);
    $stats['total_applications'] = $total_result->fetch_assoc()['count'];
    
    // Pending review (admissions not yet confirmed as students)
    $pending_sql = "SELECT COUNT(*) as count FROM admission a 
                   LEFT JOIN sbasic s ON a.sid = s.sid 
                   WHERE s.sid IS NULL";
    $pending_result = $conn->query($pending_sql);
    $stats['pending_review'] = $pending_result->fetch_assoc()['count'];
    
    // Confirmed students
    $confirmed_sql = "SELECT COUNT(*) as count FROM sbasic";
    $confirmed_result = $conn->query($confirmed_sql);
    $stats['confirmed_students'] = $confirmed_result->fetch_assoc()['count'];
    
    // Total students (same as confirmed for now)
    $stats['total_students'] = $stats['confirmed_students'];
    
    // Department breakdown
    $dept_sql = "SELECT department, COUNT(*) as count FROM admission GROUP BY department ORDER BY count DESC";
    $dept_result = $conn->query($dept_sql);
    $departments = [];
    while($row = $dept_result->fetch_assoc()) {
        $departments[] = $row;
    }
    $stats['departments'] = $departments;
    
    // Recent activity
    $activity_sql = "SELECT a.*, 'admission' as type, a.doadmission as activity_date 
                     FROM admission a 
                     ORDER BY a.doadmission DESC 
                     LIMIT 10";
    $activity_result = $conn->query($activity_sql);
    $activities = [];
    while($row = $activity_result->fetch_assoc()) {
        $activities[] = [
            'type' => 'admission',
            'message' => "New admission: " . $row['fname'] . " " . $row['lname'] . " (" . $row['department'] . ")",
            'date' => $row['activity_date'],
            'icon' => 'user-plus'
        ];
    }
    $stats['recent_activity'] = $activities;
    
    echo json_encode([
        'success' => true,
        'data' => $stats
    ]);
    
} catch(Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching statistics: ' . $e->getMessage()
    ]);
}
?>
