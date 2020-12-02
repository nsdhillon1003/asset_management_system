<?php require_once('Connections/asset_management_system.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }
	
$hostname_asset_management_system = "localhost";
$database_asset_management_system = "asset management system";
$username_asset_management_system = "root";
$password_asset_management_system = "";	
$conn= mysqli_connect($hostname_asset_management_system, $username_asset_management_system, $password_asset_management_system) or trigger_error(mysql_error(),E_USER_ERROR); 
	
  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($conn, $theValue) : mysqli_escape_string($conn, $theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysqli_select_db($asset_management_system,$database_asset_management_system);
$query_login = "SELECT staff_id, staff_password FROM staff";
$login = mysqli_query($asset_management_system, $query_login) or die(mysql_error());
$row_login = mysqli_fetch_assoc($login);
$totalRows_login = mysqli_num_rows($login);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['staff_id'])) {
  $loginUsername=$_POST['staff_id'];
  $password=$_POST['staff_password'];
  $MM_fldUserAuthorization = "staff_type";
  $MM_redirectLoginSuccess = "staff_login_main_page.php";
  $MM_redirectLoginFailed = "access_denied.php";
  $MM_redirecttoReferrer = false;
  mysqli_select_db($asset_management_system,$database_asset_management_system);
  	
  $LoginRS__query=sprintf("SELECT staff_id, staff_password, staff_type FROM staff WHERE staff_id=%s AND staff_password=%s",
  GetSQLValueString($loginUsername, "int"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysqli_query($asset_management_system, $LoginRS__query) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'staff_type');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>
<style type= "text/css">

/*styletest.css*/

body {
	margin: 0;
	background-color: #306358;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
	padding-left: 20px;
}

h1, h2, h3 {
	margin: 0;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-weight: normal;
	color: #69D5AE;
}

h1 { font-size: 44px; }

h2 {
	font-size: x-large;
	text-align: center;
}

h3 { }

p, ul, ol {
	line-height: 240%;
	text-align: left;
	font-family: Arial, Helvetica, sans-serif;
	font-size: medium;
	color: #000000;
	font-weight: bold;
	 
}

ul, ol { }

blockquote { }

a {
	color: #FFFFFF;
}

a:hover { text-decoration: none; }


#wrapper {

}

/* Header */

#header-wrapper {
}

#header {
	width: auto;
	height: auto;
	margin: 0 auto;
	margin-top: 40px;
}

/* Menu */

ul#menu, .sub-menu
{
	list-style-type:none;
}

ul#menu li
{
	width:190px;
	text-align: center;
	position : relative;
	float: left;
	
}

ul#menu a
{
	text-decoration : none;
	display: block;
	width: 190px;
	height: 35px;
	line-height: 30px;
	background-color: #4CBB96;
	border : 1px solid  #000;
	
	
}
	
ul#menu .sub-menu a
{
	 margin-top : 0px;
	
}

ul#menu li:hover > a 
{
	background-color: #69F0BF;
}

ul#menu li:hover a:hover
{
	background-color: #69F0BF;
}

ul#menu ul.sub-menu
{
	display:none;
	position:absolute;
	top: 37px;
	left: -40px;

}

ul#menu li:hover .sub-menu
{
	display:block;
}

/* Search */

#search {
	float: right;
	width: 215px;
	height: 22px;
	margin-top: -80px;
	margin-right: 20px;
}

#search form {
	float: right;
	margin: 0;
	padding: 0px 0px 0 0;
}

#search fieldset {
	margin: 0;
	padding: 0;
	border: none;
}

#search input {
	float: right;
	font: 12px Georgia, "Times New Roman", Times, serif;
	border: none;
}

#search-text {
	width: 135px;
	height: 18px;
	padding: 3px 0 0 5px;
	background: #ECF9E4;
	color: #658453;
}

#search-submit {
	height: 21px;
	margin-left: 12px;
	color: #39561D;
}

/* Page */

#page {
	width: 990px;
	padding: 0px 0px 0px 0px;
	background-repeat: no-repeat;
	top: auto;
	background-position-x: center;
	background-position-y: top;
	padding-left: 40px;
}


/* Content */

#content {
	float: right;
	width: 600px;
	margin-top: 200px;
}


/* Footer */

#footer {
	width: 1150px;
	height: 45px;

	border-top:#000
	border-bottom:#000
	background-color: #fff;
	margin: 10;
	margin-left: 40px;
}

#footer p {
	text-align: center;
	line-height: normal;
	color: #fff;
	text-wrap: normal;
}

#footer a {
	color: #fff;
}
#wrapper #page p #button {
	text-decoration: blink;
	background-attachment: fixed;
	background-color: #fff;
	color: #FFF;
}
.table {
	font-weight: bold;
	font-size: medium;
	margin-left: 100px;
}
.submit {
	background-color: #4CBB96;
	color: #FFF;
	font: bold;
	position: absolute;
	left: 414px;
	top: 295px;
	width: 100px;
}
.link {
	color: #000;
	text-decoration: none;
	margin-left: 40px;
}
</style>

</head>



<body>
<div id="wrapper">

<div id="header">
 
				<ul id="menu">
  
		  <li><a href="homepage.php">Home</a></li>
		  <li><a>About Us</a>
            <ul class="sub-menu">
            <li><a href="mission.php">Mision</a></li>
            <li><a href="vision.php">Vision</a></li>
            <li><a href="org_chart.php">Organization Chart</a></li>
            <li><a href="contact_us.php">Contact Us</a></li>
            
            </ul>
          </li>
			
		<li><a href="equipment_details.php">Equipment Details</a>
        <ul class="sub-menu">
		<li><a href="equipment_unavailable.php">Equipment Unavailable</a></li>
        <li><a href="new_equipment.php">New Equipment</a></li>
        
         </ul>
		</li>
        
		<li><a href="real_time_view.php">Borrow Equipment</a></li>
		<li><a href="room_equipment_list.php">Room Equipment List</a></li>
		<li><a> Login</a>
        <ul class="sub-menu">
            <li><a href="staff_login.php">Staff</a></li>
            <li><a href="adminloginlatest.php">Admin</a></li>
            <li><a href="newuser.php">New User</a></li>

          </ul>
		</li>
	</ul>
		<p>&nbsp;</p>
        
	
	<!-- end #menu -->
	
</div>

<div id="page">

      <p>&nbsp;</p>
      <p>
    <h1>Staff Login</h1>
	<p>
     <form id="staff_login" name="staff_login" method="POST" action="<?php echo $loginFormAction; ?>">
   
   <table width="385" height="69" class="table">
     <tr>
       <td width="144">STAFF ID</td>
       <td width="144"><input type="text" name="staff_id" id="staff_id" /></td>
     </tr>
     <tr>
       <td width="144">STAFF PASSWORD</td>
       <td width="144"><input type="password" name="staff_password" id="staff_password" /></td>
     </tr>
   </table>
   <p>
  <input name="submit" type="submit" class="submit" id="submit" value= "Submit"/>
 
 </form></p>
     
     
    <p>&nbsp;</p>
    <p><a href="s_password_recovery.php" class="link">Forgot Password?</a></p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
  <p>&nbsp;</p>
  <div style="clear: both;">&nbsp;</div>
</div>
<!-- end #page -->
</div>
<div id="footer">
	<hr />
	<p>Copyright (c) IT Asset Management System. All rights reserved.</p>
	<hr />
	<p>&nbsp;</p>
</div>
<!-- end #footer -->
<div align=center></div>
</body>
</html>
<?php
mysqli_free_result($login);
?>
