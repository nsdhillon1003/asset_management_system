<?php require_once('Connections/asset_management_system.php'); ?>
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
	
  $logoutGoTo = "homepage.php";
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
$MM_authorizedUsers = "2";
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

$MM_restrictGoTo = "s_access_denied.php";
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $selectSQL1 = sprintf("SELECT * FROM BORROW WHERE STAFF_ID=%s AND ITEM_ID=%s AND RETURN_TIMESTAMP IS NULL LIMIT 1",
                       GetSQLValueString($_POST['staff_id'], "text"),
                       GetSQLValueString($_POST['item_id'], "int"));
  
  $insertSQL = sprintf("INSERT INTO BORROW (staff_id, item_id) VALUES (%s, %s)",
                       GetSQLValueString($_POST['staff_id'], "text"),
                       GetSQLValueString($_POST['item_id'], "int"));

  $updateSQL2 = sprintf("UPDATE ITEM SET ITEM_STATUS='BORROWED' WHERE item_id=%s",
                       GetSQLValueString($_POST['item_id'], "int"));
				   
  mysql_select_db($database_asset_management_system, $asset_management_system);
  
  $Result_0 = mysql_query($selectSQL1, $asset_management_system) or die(mysql_error());
  $row_Result_0 = mysql_fetch_assoc($Result_0);
  $borrow_id=$row_Result_0['borrow_id'];
  $item_id=$row_Result_0['item_id'];
  
  $updateSQL = sprintf("UPDATE BORROW SET RETURN_TIMESTAMP=CURRENT_TIME WHERE staff_id=%s and item_id=%s AND BORROW_ID=$borrow_id",
                       GetSQLValueString($_POST['staff_id'], "text"),
                       GetSQLValueString($_POST['item_id'], "int"));
  $updateSQL3 = sprintf("UPDATE ITEM SET ITEM_STATUS='AVAILABLE' WHERE item_id=$item_id",
                       GetSQLValueString($_POST['item_id'], "int"));
					   
					   
  if ((mysql_num_rows($Result_0) > 0)){
	$Result1 = mysql_query($updateSQL, $asset_management_system) or die(mysql_error());
	# Query to update as AVAILABLE
	$Result2 = mysql_query($updateSQL3, $asset_management_system) or die(mysql_error());
  } else {
	  $Result1 = mysql_query($insertSQL, $asset_management_system) or die(mysql_error());
	  # Query to update as BORROWED
	  $Result3 = mysql_query($updateSQL2, $asset_management_system) or die(mysql_error());
  }
}

mysql_select_db($database_asset_management_system, $asset_management_system);
$query_real_time_view = "SELECT borrow.staff_id ,staff.staff_name, borrow.item_id, item.item_name, borrow.borrow_timestamp, borrow.return_timestamp  FROM borrow, item, staff  WHERE staff.staff_id = borrow.staff_id AND  item.item_id = borrow.item_id ORDER BY borrow.borrow_timestamp ASC";
$real_time_view = mysql_query($query_real_time_view, $asset_management_system) or die(mysql_error());
$row_real_time_view = mysql_fetch_assoc($real_time_view);
$totalRows_real_time_view = mysql_num_rows($real_time_view);

mysql_select_db($database_asset_management_system, $asset_management_system);
$query_select_record = "SELECT staff_id, item_id, borrow_timestamp, return_timestamp FROM borrow";
$select_record = mysql_query($query_select_record, $asset_management_system) or die(mysql_error());
$row_select_record = mysql_fetch_assoc($select_record);
$totalRows_select_record = mysql_num_rows($select_record);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Real Time View</title>
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
	padding-bottom: 10px;
}

h2 {
	font-size: x-large;
	text-align: center;
}

h3 { }

p, ul, ol {
	line-height: 240%;
	text-align: center;
	font-family: Arial, Helvetica, sans-serif;
	font-size: medium;
	color: #FFFFFF;
	font-weight: bold;
	 
}

ul, ol { }

blockquote { }

a {
	color: #000000;
}

a:hover {
	text-decoration: none;
	font-weight: bold;
}


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
	color: #FFF;	
	
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
	font-size: 16px;
}


/* Content */

#content {
	float: right;
	width: 600px;
	margin-top: 200px;
}


/* Footer */

#footer {
	width: 965px;
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

table tr td b {
	text-align: center;
}
.table {
	font-size: 16px;
	margin-top: 20px;
	margin-left: 40px;
}
.text {
	margin-left: 40px;
}
</style>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
</head>


<body>
<body>
<div id="wrapper">

<div id="header">
 
				<ul id="menu">
  
		   <li><a href="staff_login_main_page.php">Home</a></li>
		  <li><a>About Us</a>
            <ul class="sub-menu">
            <li><a href="s_mission.php">Mision</a></li>
            <li><a href="s_vision.php">Vision</a></li>
            <li><a href="s_org_chart.php">Organization Chart</a></li>
            <li><a href="s_contact_us.php">Contact Us</a></li>
            
            </ul>
          </li>
			
		<li><a href="s_equipment_details.php">Equipment Details</a>
        <ul class="sub-menu">
		<li><a href="s_equipment_unavailable.php">Equipment Unavailable</a></li>
        <li><a href="s_new_equipment.php">New Equipment</a></li>
        
         </ul>
		</li>
        
		  
		<li><a href="s_real_time_view.php">Borrow Equipment</a></li>
		<li><a href="s_room_equipment_list.php">Room Equipment List</a></li>
		<li><a href="<?php echo $logoutAction ?>"> Logout</a></li>
	</ul>
		<p>&nbsp;</p>
        
	
	<!-- end #menu -->
	
</div>
<div id="page">
  <h1>Borrow Equipment</h1>
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>" class="text">
  <label for="staff_id">Staff ID</label>
  <span id="sprytextfield1">
  <input type="text" name="staff_id" id="staff_id" />
  <span class="textfieldRequiredMsg">A value is required.</span></span>
  <label for="item_id">Item ID</label>
  <span id="sprytextfield2">
  <input type="text" name="item_id" id="item_id" />
  <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span>
  <input type="submit" name="submit" id="submit" value="Submit" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<table width="945" border="1" cellpadding="1" cellspacing="1" class="table">
  <tr>
    <td><b>Staff ID</b></td>
    <td><b>Staff Name</b></td>
    <td><b>Item ID</b></td>
    <td><b>Item Name</b></td>
    <td><b>Borrow Timestamp</b></td>
    <td><b>Return Timestamp</b></td>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="staff_details.php?staff_id=<?php echo $row_real_time_view['staff_id']; ?>"><?php echo $row_real_time_view['staff_id']; ?></a></td>
      <td><?php echo $row_real_time_view['staff_name']; ?></td>
      <td><a href="details_of_item.php?item_id=<?php echo $row_real_time_view['item_id']; ?>"><?php echo $row_real_time_view['item_id']; ?></a></td>
      <td><?php echo $row_real_time_view['item_name']; ?></td>
      <td><?php echo $row_real_time_view['borrow_timestamp']; ?></td>
      <td><?php echo $row_real_time_view['return_timestamp']; ?></td>
    </tr>
    <?php } while ($row_real_time_view = mysql_fetch_assoc($real_time_view)); ?>
</table>
<h3>&nbsp; </h3>
<p>&nbsp;</p>
 <p>&nbsp;</p>
 <p>&nbsp;</p>
 <p>&nbsp;</p>
 <p>&nbsp;</p>
 <p><br />
 </p>
</div>
<div id="footer">
  <hr />
  <p>Copyright (c) IT Asset Management System. All rights reserved.. </p>
  <hr />
  <p>&nbsp;</p>
</div>
<!-- end #footer -->
<div align=center></div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "integer");
</script>
</body>

</html>
<?php
mysql_free_result($real_time_view);

mysql_free_result($select_record);
?>
