<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['id'])){
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

include '../config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $sid = mysqli_real_escape_string($conn, $_POST['sid']);
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname'] ?? '');
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $programme = mysqli_real_escape_string($conn, $_POST['programme'] ?? '');
    $department = mysqli_real_escape_string($conn, $_POST['department'] ?? '');
    $batch = mysqli_real_escape_string($conn, $_POST['batch'] ?? '');
    $dob = $_POST['dob'] ? mysqli_real_escape_string($conn, $_POST['dob']) : 'NULL';
    $blood = mysqli_real_escape_string($conn, $_POST['blood'] ?? '');
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile'] ?? '');
    $pmobile = mysqli_real_escape_string($conn, $_POST['pmobile'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $offemail = mysqli_real_escape_string($conn, $_POST['offemail'] ?? '');
    $languages = mysqli_real_escape_string($conn, $_POST['languages'] ?? '');
    $aadhar = mysqli_real_escape_string($conn, $_POST['aadhar'] ?? '');
    $saadhar = mysqli_real_escape_string($conn, $_POST['saadhar'] ?? '');
    $pan = mysqli_real_escape_string($conn, $_POST['pan'] ?? '');
    $span = mysqli_real_escape_string($conn, $_POST['span'] ?? '');
    $hosday = mysqli_real_escape_string($conn, $_POST['hosday'] ?? '');
    $hosname = mysqli_real_escape_string($conn, $_POST['hosname'] ?? '');
    $room = mysqli_real_escape_string($conn, $_POST['room'] ?? '');
    $stay = mysqli_real_escape_string($conn, $_POST['stay'] ?? '');
    $busno = $_POST['busno'] ? intval($_POST['busno']) : 'NULL';
    $paddress = mysqli_real_escape_string($conn, $_POST['paddress'] ?? '');
    $taddress = mysqli_real_escape_string($conn, $_POST['taddress'] ?? '');
    $city = mysqli_real_escape_string($conn, $_POST['city'] ?? '');
    $state = mysqli_real_escape_string($conn, $_POST['state'] ?? '');
    $zip = mysqli_real_escape_string($conn, $_POST['zip'] ?? '');
    $country = mysqli_real_escape_string($conn, $_POST['country'] ?? '');
    $doadmission = $_POST['doadmission'] ? mysqli_real_escape_string($conn, $_POST['doadmission']) : 'NULL';
    $admcate = mysqli_real_escape_string($conn, $_POST['admcate'] ?? '');
    $admtype = mysqli_real_escape_string($conn, $_POST['admtype'] ?? '');
    $religion = mysqli_real_escape_string($conn, $_POST['religion'] ?? '');
    $socstrata = mysqli_real_escape_string($conn, $_POST['socstrata'] ?? '');
    $caste = mysqli_real_escape_string($conn, $_POST['caste'] ?? '');
    $nationality = mysqli_real_escape_string($conn, $_POST['nationality'] ?? '');
    $firstgra = mysqli_real_escape_string($conn, $_POST['firstgra'] ?? '');
    $cutoff = $_POST['cutoff'] ? intval($_POST['cutoff']) : 'NULL';
    $exam_status = mysqli_real_escape_string($conn, $_POST['exam_status'] ?? '');
    $exam_mark = mysqli_real_escape_string($conn, $_POST['exam_mark'] ?? '');
    $strengths = mysqli_real_escape_string($conn, $_POST['Strengths'] ?? '');
    $weaknesses = mysqli_real_escape_string($conn, $_POST['Weaknesses'] ?? '');
    $opportunities = mysqli_real_escape_string($conn, $_POST['Opportunities'] ?? '');
    $threats = mysqli_real_escape_string($conn, $_POST['Threats'] ?? '');
    $guarname = mysqli_real_escape_string($conn, $_POST['guarname'] ?? '');
    $guarmobile = mysqli_real_escape_string($conn, $_POST['guarmobile'] ?? '');
    $guaraddress = mysqli_real_escape_string($conn, $_POST['guaraddress'] ?? '');
    $admission_id = $_POST['admission_id'] ? intval($_POST['admission_id']) : 'NULL';
    $ayear_id = $_POST['ayear_id'] ? intval($_POST['ayear_id']) : 'NULL';
    
    // Check if student already exists
    $check_sql = "SELECT sid FROM sbasic WHERE sid = '$sid'";
    $check_result = $conn->query($check_sql);
    
    if($check_result && $check_result->num_rows > 0) {
        // Update existing record
        $sql = "UPDATE sbasic SET 
                fname = '$fname',
                lname = '$lname',
                gender = '$gender',
                programme = '$programme',
                department = '$department',
                batch = '$batch',
                dob = " . ($dob !== 'NULL' ? "'$dob'" : 'NULL') . ",
                blood = '$blood',
                mobile = '$mobile',
                pmobile = '$pmobile',
                email = '$email',
                offemail = '$offemail',
                languages = '$languages',
                aadhar = '$aadhar',
                saadhar = '$saadhar',
                pan = '$pan',
                span = '$span',
                hosday = '$hosday',
                hosname = '$hosname',
                room = '$room',
                stay = '$stay',
                busno = " . ($busno !== 'NULL' ? $busno : 'NULL') . ",
                paddress = '$paddress',
                taddress = '$taddress',
                city = '$city',
                state = '$state',
                zip = '$zip',
                country = '$country',
                doadmission = " . ($doadmission !== 'NULL' ? "'$doadmission'" : 'NULL') . ",
                admcate = '$admcate',
                admtype = '$admtype',
                religion = '$religion',
                socstrata = '$socstrata',
                caste = '$caste',
                nationality = '$nationality',
                firstgra = '$firstgra',
                cutoff = " . ($cutoff !== 'NULL' ? $cutoff : 'NULL') . ",
                exam_status = '$exam_status',
                exam_mark = '$exam_mark',
                Strengths = '$strengths',
                Weaknesses = '$weaknesses',
                Opportunities = '$opportunities',
                Threats = '$threats',
                guarname = '$guarname',
                guarmobile = '$guarmobile',
                guaraddress = '$guaraddress',
                status = 1
                WHERE sid = '$sid'";
    } else {
        // Insert new record
        $sql = "INSERT INTO sbasic (
                sid, fname, lname, gender, programme, department, batch, dob, blood, mobile, pmobile, 
                email, offemail, languages, aadhar, saadhar, pan, span, hosday, hosname, room, stay, 
                busno, paddress, taddress, city, state, zip, country, doadmission, admcate, admtype, 
                religion, socstrata, caste, nationality, firstgra, cutoff, exam_status, exam_mark, 
                Strengths, Weaknesses, Opportunities, Threats, guarname, guarmobile, guaraddress, 
                admission_id, ayear_id, status
            ) VALUES (
                '$sid', '$fname', '$lname', '$gender', '$programme', '$department', '$batch',
                " . ($dob !== 'NULL' ? "'$dob'" : 'NULL') . ", '$blood', '$mobile', '$pmobile',
                '$email', '$offemail', '$languages', '$aadhar', '$saadhar', '$pan', '$span',
                '$hosday', '$hosname', '$room', '$stay', " . ($busno !== 'NULL' ? $busno : 'NULL') . ",
                '$paddress', '$taddress', '$city', '$state', '$zip', '$country',
                " . ($doadmission !== 'NULL' ? "'$doadmission'" : 'NULL') . ", '$admcate', '$admtype',
                '$religion', '$socstrata', '$caste', '$nationality', '$firstgra',
                " . ($cutoff !== 'NULL' ? $cutoff : 'NULL') . ", '$exam_status', '$exam_mark',
                '$strengths', '$weaknesses', '$opportunities', '$threats', '$guarname', '$guarmobile',
                '$guaraddress', " . ($admission_id !== 'NULL' ? $admission_id : 'NULL') . ",
                " . ($ayear_id !== 'NULL' ? $ayear_id : 'NULL') . ", 1
            )";
    }
    
    if($conn->query($sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Student details saved successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save student details: ' . $conn->error]);
    }
    
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$conn->close();
?>
