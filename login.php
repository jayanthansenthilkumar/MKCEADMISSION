<?php
session_start();
header('Content-Type: application/json');

include 'config.php';

if(isset($_POST['id'], $_POST['pass'])) {
    $id = $conn->real_escape_string($_POST['id']);
    $pass = $_POST['pass'];

    $sql = "SELECT * FROM faculty WHERE id='$id' AND pass='$pass'";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['id']; // Add username session for admission.php compatibility
        echo json_encode(['status'=>'success']);
    } else {
        echo json_encode(['status'=>'error','message'=>'Invalid id or pass']);
    }
} else {
    echo json_encode(['status'=>'error','message'=>'Please enter id and pass']);
}

$conn->close();
?>
