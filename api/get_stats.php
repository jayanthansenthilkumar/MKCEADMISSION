<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['id'])){
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

include '../config.php';

// Get dashboard statistics
$stats = [
    'total_admissions' => 0,
    'pending' => 0,
    'confirmed' => 0,
    'rejected' => 0
];

// Total admissions
$total_sql = "SELECT COUNT(*) as count FROM admission";
$total_result = $conn->query($total_sql);
if($total_result) {
    $stats['total_admissions'] = $total_result->fetch_assoc()['count'];
}

// Pending (ADMITTED status)
$pending_sql = "SELECT COUNT(*) as count FROM admission WHERE status = 'ADMITTED'";
$pending_result = $conn->query($pending_sql);
if($pending_result) {
    $stats['pending'] = $pending_result->fetch_assoc()['count'];
}

// Confirmed
$confirmed_sql = "SELECT COUNT(*) as count FROM admission WHERE status = 'CONFIRMED'";
$confirmed_result = $conn->query($confirmed_sql);
if($confirmed_result) {
    $stats['confirmed'] = $confirmed_result->fetch_assoc()['count'];
}

// Rejected
$rejected_sql = "SELECT COUNT(*) as count FROM admission WHERE status = 'REJECTED'";
$rejected_result = $conn->query($rejected_sql);
if($rejected_result) {
    $stats['rejected'] = $rejected_result->fetch_assoc()['count'];
}

echo json_encode(['status'=>'success','data'=>$stats]);

$conn->close();
?>
