<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['id'])){
    echo json_encode(['success'=>false,'message'=>'Unauthorized']);
    exit;
}

include '../config.php';

try {
    // Test database connection
    $conn->query("SELECT 1");
    
    // Test if tables exist
    $tables = ['admission', 'sbasic', 'faculty', 'ayear'];
    $tableStatus = [];
    
    foreach($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        $tableStatus[$table] = $result && $result->num_rows > 0;
    }
    
    // Get basic counts
    $counts = [];
    if($tableStatus['admission']) {
        $result = $conn->query("SELECT COUNT(*) as count FROM admission");
        $counts['admissions'] = $result ? $result->fetch_assoc()['count'] : 0;
    }
    
    if($tableStatus['sbasic']) {
        $result = $conn->query("SELECT COUNT(*) as count FROM sbasic");
        $counts['students'] = $result ? $result->fetch_assoc()['count'] : 0;
    }
    
    echo json_encode([
        'success' => true,
        'database_connected' => true,
        'tables' => $tableStatus,
        'counts' => $counts,
        'message' => 'System health check passed'
    ]);
    
} catch(Exception $e) {
    echo json_encode([
        'success' => false,
        'database_connected' => false,
        'error' => $e->getMessage()
    ]);
}

$conn->close();
?>
