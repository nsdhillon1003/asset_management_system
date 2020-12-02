<?php @session_start();?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "homepagetest.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "access_denied.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php require_once('Connections/asset_management_system.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE staff SET staff_type=%s, staff_password=%s, staff_name=%s, staff_faculty=%s, staff_email=%s, staff_phone=%s WHERE staff_id=%s",
                       GetSQLValueString($_POST['staff_type'], "int"),
                       GetSQLValueString($_POST['staff_password'], "text"),
                       GetSQLValueString($_POST['staff_name'], "text"),
                       GetSQLValueString($_POST['staff_faculty'], "text"),
                       GetSQLValueString($_POST['staff_email'], "text"),
                       GetSQLValueString($_POST['staff_phone'], "text"),
                       GetSQLValueString($_POST['staff_id'], "text"));

  mysql_select_db($database_asset_management_system, $asset_management_system);
  $Result1 = mysql_query($updateSQL, $asset_management_system) or die(mysql_error());

  $updateGoTo = "updated_user_account.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_update_staff = "-1";
if (isset($_GET['staff_id'])) {
  $colname_update_staff = $_GET['staff_id'];
}
mysql_select_db($database_asset_management_system, $asset_management_system);
$query_update_staff = sprintf("SELECT * FROM staff WHERE staff_id = %s", GetSQLValueString($colname_update_staff, "text"));
$update_staff = mysql_query($query_update_staff, $asset_management_system) or die(mysql_error());
$row_update_staff = mysql_fetch_assoc($update_staff);
$totalRows_update_staff = "-1";
if (isset($_SESSION['MM_Username'])) {
  $totalRows_update_staff = $_SESSION['MM_Username'];
}

$colname_update_staff = "-1";
if (isset($_GET['staff_id'])) {
  $colname_update_staff = $_GET['staff_id'];
}
mysql_select_db($database_asset_management_system, $asset_management_system);
$query_update_staff = sprintf("SELECT * FROM staff WHERE staff_id = %s", GetSQLValueString($colname_update_staff, "text"));
$update_staff = mysql_query($query_update_staff, $asset_management_system) or die(mysql_error());
$row_update_staff = mysql_fetch_assoc($update_staff);
$totalRows_update_staff = mysql_num_rows($update_staff);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Update Staff Account</title>
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
	color: #990033;
}

h1 {
	font-size: 44px;
	margin-left: 100px;
}

h2 {
	font-size: x-large;
	text-align: center;
}

h3 { }

p, ul, ol {
	line-height: 20px;
	text-align: left;
	font-family: Arial, Helvetica, sans-serif;
	font-size: medium;
	color: #000000;
	font-weight: bold;
	text-height: font-size;
	padding-left: 40px;
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
	width: 190px;
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


#menu .current_page_item a {
	padding-left: 0px;
	color: #FFFFFF;
}
/*Right Menu */

ul#rmenu, .rsub-menu
{
	list-style-type: none;
	position: absolute;
	left: 997px;
	top: 114px;
	width: 160px;
}

ul#rmenu li
{
	width: 165px;
	text-align: center;
	position : relative;
	float: right;	
}

ul#rmenu a
{
	text-decoration : none;
	display: block;
	width: 165px;
	height: 35px;
	line-height: 30px;
	background-color: #4CBB96;
	border : 1px solid  #000;
	
	
}
	
ul#rmenu .rsub-menu a
{
	 margin-top : 0px;
	
}

ul#rmenu li:hover > a 
{
	background-color: #69F0BF;
}

ul#rmenu li:hover a:hover
{
	background-color: #69F0BF;
}

ul#rmenu ul.rsub-menu
{
	display:none;
	position:relative;
	top: 37px;
	left: 0px;

}

ul#rmenu li:hover .rsub-menu
{
	display: block;
	position: fixed;
	margin-top: 129px;
	margin-left: 831px;
}


#rmenu .current_page_item a {
	padding-left: 0px;
	color: #FFFFFF;
}
	<!-- end #rmenu -->

/* Page */

#page {
	width: 990px;
	padding: 0px 0px 0px 0px;
	background-repeat: no-repeat;
	top: auto;
	background-position-x: center;
	background-position-y: top;
	padding-left: 50px;
	padding-top: -100px;
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
	background-color:#FFF;
	margin: 10;
	margin-left: 40px;
}

#footer p {
	text-align: center;
	line-height: normal;
	color: #4CBB96;
	text-wrap: normal;
}

#footer a {
	color: #FFF;
}
#wrapper #page p #button {
	text-decoration: blink;
	background-attachment: fixed;
	background-color: #69F0BF;
	color: #FFF;
}
.submit {
	position: absolute;
	left: 63px;
	width: 93px;
	/* [disabled]top: 476px; */
}
.form {
	margin-left: 100px;
	color: #000;
	font-weight: bold;
	font-size: medium;
	padding-top: 20px;
}
.submit1 {
	background-color: #906;
	color: #FFF;
	position: absolute;
	left: 830px;
	top: 479px;
	width: 100px;
}
.table {
	background-color: #306358;
	font-size: medium;
	font-weight: normal;
	margin-left: 150px;
	padding-top: 20px;
}
.footer {
	color: #4CBB96;
}
</style>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
</head>



<body>

<div id="wrapper">

<div id="header">
 
				<ul id="menu">
  
		  <li><a href="adminloginmainpage.php">Home</a></li>
		  <li><a>About Us</a>
            <ul class="sub-menu">
            <li><a href="a_mission.php">Mision</a></li>
            <li><a href="a_vision.php">Vision</a></li>
            <li><a href="a_org_chart.php">Organization Chart</a></li>
            <li><a href="a_contact_us.php">Contact Us</a></li>
            
            </ul>
          </li>
			
		<li><a href="a_equipment_details.php">Equipment Details</a>
        <ul class="sub-menu">
		<li><a href="a_equipment_unavailable.php">Equipment Unavailable</a></li>
        <li><a href="a_new_equipment.php">New Equipment</a></li>
        
         </ul>
		</li>
        
		<li><a href="real_time_view.php">Borrow Equipment</a></li>
		<li><a href="room_equipment_list.php">Room Equipment List</a></li>
          <li><a href="<?php echo $logoutAction ?>">Logout</a></li>
		
	</ul>
		<p>&nbsp;</p>
        
	
	<!-- end #menu -->
	
</div>

<div align="left" id="page">

      <p>&nbsp;</p>
    <p>
<h1> Update Staff Account </h1>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" class="table">
  <table width="676" align="left">
    <tr valign="baseline">
      <td width="138" align="left" nowrap="nowrap"><strong>Staff Type</strong></td>
      <td width="526"><span id="sprytextfield6">
        <b>:</b> 
        <input type="text" name="staff_type" value="<?php echo htmlentities($row_update_staff['staff_type'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
        <span class="textfieldRequiredMsg">A value is required.</span></span><sup>(1= Admin, 2= Staff)</sup>
</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="left"><strong>Staff Id</strong></td>
      <td> <b>:</b> <?php echo $row_update_staff['staff_id']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="left"><strong>Staff Password</strong></td>
      <td><span id="sprytextfield5"> <b>:</b> 
          <input type="password" name="staff_password" value="<?php echo htmlentities($row_update_staff['staff_password'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
        <span class="textfieldRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="left"><strong>Staff Name</strong></td>
      <td><span id="sprytextfield4">
         <b>:</b> 
        <input type="text" name="staff_name" value="<?php echo htmlentities($row_update_staff['staff_name'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
        <span class="textfieldRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="left"><strong>Staff Faculty</strong></td>
      <td><span id="sprytextfield3">
       <b>:</b>  
      <input type="text" name="staff_faculty" value="<?php echo htmlentities($row_update_staff['staff_faculty'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="left"><strong>Staff Email</strong></td>
      <td><span id="sprytextfield2">
       <b>:</b>  
      <input type="text" name="staff_email" value="<?php echo htmlentities($row_update_staff['staff_email'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
      <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="left"><strong>Staff Phone</strong></td>
      <td><span id="sprytextfield1">
       <b>:</b> 
      <input type="text" name="staff_phone" value="<?php echo htmlentities($row_update_staff['staff_phone'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
      <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update record" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="staff_id" value="<?php echo $row_update_staff['staff_id']; ?>" />
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</form>
     
     
    <p>&nbsp;</p>
  <p>&nbsp;</p>
  <div style="clear: both;">&nbsp;
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
  </div>
</div>
<!-- end #page -->
</div>
<!--  #rmenu -->
 <ul id="rmenu" name="rmenu">
  
		  <li><a href="staff_history.php">Staff History</a></li>
		  <li><a>Item</a>
            <ul class="rsub-menu">
            <li><a href="add_item.php">Add Item</a></li>
            <li><a href="update_item.php">Update Item</a></li>
            <li><a href="item_details.php">Item Details</a></li>
       
            
            </ul>
    </li>
			
	
		<li><a href="stock_control.php">Stock Control</a></li>
		<li><a href="#">Generate Report</a></li>
        <li><a href="update_account.php?staff_id=<?php echo @$row_update['staff_id']; ?>">Update Account</a></li>
       <li><a href="staff_account_details.php">Update Staff Account</a></li>
	</ul>
        
       
        
		
	<!-- end #rmenu -->
<div id="footer">
	<hr />
	<p>Copyright (c) Computer Operations <span class="footer">and Services Unit. All rights reserv</span>ed. </p>
	<hr />
	<p>&nbsp;</p>
</div>
</option>
<!-- end #footer -->
<div align=center></div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "integer");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "email");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5");
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6");
</script>
</body>
</html>
<?php
mysql_free_result($update_staff);
?>
