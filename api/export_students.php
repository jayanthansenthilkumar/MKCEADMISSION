<?php
session_start();

if(!isset($_SESSION['id'])){
    header("Location: ../index.php");
    exit;
}

include '../config.php';

// Set headers for Excel download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="MKCE_Students_Data_' . date('Y-m-d') . '.xls"');
header('Cache-Control: max-age=0');

// Get all students data
$sql = "SELECT s.*, ay.ayear 
        FROM sbasic s 
        LEFT JOIN ayear ay ON s.ayear_id = ay.id 
        ORDER BY s.sid ASC";

$result = $conn->query($sql);

// Start HTML table for Excel
echo '<table border="1">';
echo '<tr style="background-color: #4b6cb7; color: white; font-weight: bold;">';
echo '<th>Student ID</th>';
echo '<th>First Name</th>';
echo '<th>Last Name</th>';
echo '<th>Gender</th>';
echo '<th>Date of Birth</th>';
echo '<th>Blood Group</th>';
echo '<th>Mobile</th>';
echo '<th>Parent Mobile</th>';
echo '<th>Email</th>';
echo '<th>Programme</th>';
echo '<th>Department</th>';
echo '<th>Batch</th>';
echo '<th>Academic Year</th>';
echo '<th>Admission Category</th>';
echo '<th>Admission Type</th>';
echo '<th>Religion</th>';
echo '<th>Caste</th>';
echo '<th>Nationality</th>';
echo '<th>Accommodation</th>';
echo '<th>Hostel Name</th>';
echo '<th>Room Number</th>';
echo '<th>Bus Number</th>';
echo '<th>Guardian Name</th>';
echo '<th>Guardian Mobile</th>';
echo '<th>Permanent Address</th>';
echo '<th>City</th>';
echo '<th>State</th>';
echo '<th>ZIP Code</th>';
echo '<th>Country</th>';
echo '<th>Aadhar Number</th>';
echo '<th>PAN Number</th>';
echo '<th>First Graduate</th>';
echo '<th>Cutoff Mark</th>';
echo '<th>Languages Known</th>';
echo '</tr>';

if($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['sid']) . '</td>';
        echo '<td>' . htmlspecialchars($row['fname']) . '</td>';
        echo '<td>' . htmlspecialchars($row['lname']) . '</td>';
        echo '<td>' . htmlspecialchars($row['gender']) . '</td>';
        echo '<td>' . htmlspecialchars($row['dob']) . '</td>';
        echo '<td>' . htmlspecialchars($row['blood']) . '</td>';
        echo '<td>' . htmlspecialchars($row['mobile']) . '</td>';
        echo '<td>' . htmlspecialchars($row['pmobile']) . '</td>';
        echo '<td>' . htmlspecialchars($row['email']) . '</td>';
        echo '<td>' . htmlspecialchars($row['programme']) . '</td>';
        echo '<td>' . htmlspecialchars($row['department']) . '</td>';
        echo '<td>' . htmlspecialchars($row['batch']) . '</td>';
        echo '<td>' . htmlspecialchars($row['ayear']) . '</td>';
        echo '<td>' . htmlspecialchars($row['admcate']) . '</td>';
        echo '<td>' . htmlspecialchars($row['admtype']) . '</td>';
        echo '<td>' . htmlspecialchars($row['religion']) . '</td>';
        echo '<td>' . htmlspecialchars($row['caste']) . '</td>';
        echo '<td>' . htmlspecialchars($row['nationality']) . '</td>';
        echo '<td>' . htmlspecialchars($row['hosday']) . '</td>';
        echo '<td>' . htmlspecialchars($row['hosname']) . '</td>';
        echo '<td>' . htmlspecialchars($row['room']) . '</td>';
        echo '<td>' . htmlspecialchars($row['busno']) . '</td>';
        echo '<td>' . htmlspecialchars($row['guarname']) . '</td>';
        echo '<td>' . htmlspecialchars($row['guarmobile']) . '</td>';
        echo '<td>' . htmlspecialchars($row['paddress']) . '</td>';
        echo '<td>' . htmlspecialchars($row['city']) . '</td>';
        echo '<td>' . htmlspecialchars($row['state']) . '</td>';
        echo '<td>' . htmlspecialchars($row['zip']) . '</td>';
        echo '<td>' . htmlspecialchars($row['country']) . '</td>';
        echo '<td>' . htmlspecialchars($row['aadhar']) . '</td>';
        echo '<td>' . htmlspecialchars($row['pan']) . '</td>';
        echo '<td>' . htmlspecialchars($row['firstgra']) . '</td>';
        echo '<td>' . htmlspecialchars($row['cutoff']) . '</td>';
        echo '<td>' . htmlspecialchars($row['languages']) . '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="33">No student records found</td></tr>';
}

echo '</table>';

$conn->close();
?>
