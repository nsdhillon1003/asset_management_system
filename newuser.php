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
	
  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($conn, $theValue) : mysqli_escape_string($conn,$theValue);

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "new_user")) {
  $insertSQL = sprintf("INSERT INTO staff (staff_type, staff_id, staff_password, staff_name, staff_department, staff_email, staff_phone) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['radiobutton'], "int"),
                       GetSQLValueString($_POST['staff_id'], "text"),
                       GetSQLValueString($_POST['staff_password'], "text"),
                       GetSQLValueString($_POST['staff_name'], "text"),
                       GetSQLValueString($_POST['staff_department'], "text"),
                       GetSQLValueString($_POST['staff_email'], "text"),
                       GetSQLValueString($_POST['staff_phone'], "int"));

  mysqli_select_db($asset_management_system,$database_asset_management_system );
  $Result1 = mysqli_query($asset_management_system, $insertSQL) or die(mysql_error());

  $insertGoTo = "staff_login.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysqli_select_db($asset_management_system,$database_asset_management_system );
$query_user_request = "SELECT * FROM staff";
$user_request = mysqli_query($asset_management_system, $query_user_request) or die(mysql_error());
$row_user_request = mysqli_fetch_assoc($user_request);
$totalRows_user_request = mysqli_num_rows($user_request);
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
	color: #4CBB96;
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
	color: #FFFFFF;
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
#submit {
	background-color: #4CBB96;
	color: #FFF;
	font: bold;
	position: absolute;
	left: 414px;
	top: 455px;
	width: 100px;
}
</style>
	
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
</head>



<body>
<div id="wrapper">

<div id="header">
 
				<ul id="menu">
  
		  <li><a href="homepage.php">Home</a></li>
		  <li><a>About us</a>
            <ul class="sub-menu">
            <li><a href="mission.php">Mision</a></li>
            <li><a href="vision.php">Vision</a></li>
            <li><a href="newuser.php">Organization Chart</a></li>
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
      <h1>New User </h1>
  <form action="<?php echo $editFormAction; ?>" name="new_user" id="new_user"form method="POST">
  
  <input type="radio" name="radiobutton" value="2"   checked> Staff

  <p></p>
 
    <table width="838" height="161" class="table">
     <tr>
       <td width="141" height="24" class="table">ID</td>
       <td width="685"><span id="sprytextfield1">
         <input type="text" name="staff_id" id="staff_id" />
        <span class="textfieldRequiredMsg">An ID is required.</span></span></td>
     </tr>
     <tr>
       <td height="25" class="table">PASSWORD</td>
       <td><span id="sprytextfield2">
         <input type="password" name="staff_password" id="staff_password" />
         <span class="textfieldRequiredMsg">A password is required.</span></span><sup>*hint: use uppercase,lowercase and numbers(max 8 characters) (eg:P@sSw0rD)</sup>
        </p></td>
     </tr>
     <tr>
       <td height="24" class="table">NAME</td>
       <td><span id="sprytextfield3">
         <input type="text" name="staff_name" id="staff_name" />
        <span class="textfieldRequiredMsg">A name is required.</span></span></td>
     </tr>
     <tr>
       <td height="24" class="table">DEPARTMENT</td>
       <td><span id="sprytextfield4">
       <input type="text" name="staff_department" id="staff_department" />
       <span class="textfieldRequiredMsg">A department name is required.</span></span></td>
     </tr>
     <tr>
       <td height="24" class="table">EMAIL ADDRESS</td>
       <td><span id="sprytextfield5">
       <input type="text" name="staff_email" id="staff_email" />
       <span class="textfieldRequiredMsg">An email address is required.</span><span class="textfieldInvalidFormatMsg">Ex: something@example.com</span></span></td>
     </tr>
     <tr>
       <td height="24" class="table">PHONE NUMBER</td>
       <td><span id="sprytextfield6">
       <input type="text" name="staff_phone" id="staff_phone" />
      <span class="textfieldRequiredMsg">A phone number is required.</span><span class="textfieldInvalidFormatMsg">Ex:60161234567</span></span></tr>
   </table>
   <p>
           <input type="submit" name="submit" id="submit" value="Submit" />
           <input type="hidden" name="MM_insert" value="user_request" />
           <input type="hidden" name="MM_insert" value="new_user" />
  </form>
<p></p>      

     
     
    <p>&nbsp;</p>
  <p>&nbsp;</p>
	<p>&nbsp;</p>
  <div style="clear: both;">&nbsp;</div>
</div>
<!-- end #page -->
</div>
<div id="footer">
	<hr />
<p>Copyright (c) IT Asset Management System. All rights reserved.</p>	<hr />
	<p>&nbsp;</p>
</div>
<!-- end #footer -->
<div align=center></div>

<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "none");
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "email");
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "integer");
</script>
</body>
</html>
<?php
mysqli_free_result($user_request);
?>
