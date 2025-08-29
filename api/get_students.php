<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['id'])){
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

include '../config.php';

// Get all students from sbasic table
$sql = "SELECT s.*, ay.ayear 
        FROM sbasic s 
        LEFT JOIN ayear ay ON s.ayear_id = ay.id 
        ORDER BY s.sid ASC";

$result = $conn->query($sql);
$students = [];

if($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

echo json_encode(['status'=>'success','data'=>$students]);

$conn->close();
?>
