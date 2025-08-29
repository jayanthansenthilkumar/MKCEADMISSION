<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['id'])){
    echo json_encode(['success'=>false,'message'=>'Unauthorized']);
    exit;
}

include '../config.php';

// Get dashboard statistics
$stats = [
    'total_applications' => 0,
    'pending_review' => 0,
    'confirmed_students' => 0,
    'total_students' => 0,
    'total_admissions' => 0,
    'pending' => 0,
    'confirmed' => 0,
    'rejected' => 0
];

try {
    // Total applications in admission table
    $total_sql = "SELECT COUNT(*) as count FROM admission";
    $total_result = $conn->query($total_sql);
    if($total_result) {
        $stats['total_applications'] = $total_result->fetch_assoc()['count'];
        $stats['total_admissions'] = $stats['total_applications']; // For backward compatibility
    }

    // Pending review (ADMITTED status)
    $pending_sql = "SELECT COUNT(*) as count FROM admission WHERE status = 'ADMITTED'";
    $pending_result = $conn->query($pending_sql);
    if($pending_result) {
        $stats['pending_review'] = $pending_result->fetch_assoc()['count'];
        $stats['pending'] = $stats['pending_review']; // For backward compatibility
    }

    // Confirmed students (CONFIRMED status in admission table)
    $confirmed_admission_sql = "SELECT COUNT(*) as count FROM admission WHERE status = 'CONFIRMED'";
    $confirmed_admission_result = $conn->query($confirmed_admission_sql);
    if($confirmed_admission_result) {
        $stats['confirmed'] = $confirmed_admission_result->fetch_assoc()['count'];
    }

    // Total confirmed students in sbasic table
    $sbasic_sql = "SELECT COUNT(*) as count FROM sbasic";
    $sbasic_result = $conn->query($sbasic_sql);
    if($sbasic_result) {
        $stats['confirmed_students'] = $sbasic_result->fetch_assoc()['count'];
        $stats['total_students'] = $stats['confirmed_students'];
    }

    // Rejected
    $rejected_sql = "SELECT COUNT(*) as count FROM admission WHERE status = 'REJECTED'";
    $rejected_result = $conn->query($rejected_sql);
    if($rejected_result) {
        $stats['rejected'] = $rejected_result->fetch_assoc()['count'];
    }

    echo json_encode(['success'=>true,'data'=>$stats]);

} catch(Exception $e) {
    echo json_encode(['success'=>false,'message'=>'Database error: ' . $e->getMessage()]);
}

$conn->close();
?>
