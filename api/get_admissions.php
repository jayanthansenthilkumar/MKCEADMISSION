<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['id'])){
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

include '../config.php';

// Get all admission records
$sql = "SELECT a.*, ay.ayear 
        FROM admission a 
        LEFT JOIN ayear ay ON a.ayear_id = ay.id 
        ORDER BY a.created_at DESC";

$result = $conn->query($sql);
$admissions = [];

if($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $admissions[] = $row;
    }
}

echo json_encode(['status'=>'success','data'=>$admissions]);

$conn->close();
?>
