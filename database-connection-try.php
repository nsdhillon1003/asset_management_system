<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = mysqli_connect($servername, $username, $password); 
mysqli_select_db($conn, "asset management system");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully";

$staff_id=@$_POST['staff_id'];
$staff_password=@$_POST['staff_password'];
$staff_name=@$_POST['staff_name'];
$staff_faculty=@$_POST['staff_faculty'];
$staff_email=@$_POST['staff_email'];
$staff_phone=@$_POST['staff_phone'];

$query="INSERT INTO staff(staff_id,  staff_password, staff_name, staff_faculty, staff_email, admin_phone) VALUES (' " .$staff_id. " ',' " .$staff_password. " ',' " .$staff_name. " ',' " .$staff_faculty. " ',' " .$staff_email. " ',' " .$staff_phone. " ')";


mysqli_query($conn, $query) or die( 'Error');
echo " successfully added";

?>
