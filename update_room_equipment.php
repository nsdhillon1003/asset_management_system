<?php require_once('Connections/asset_management_system.php'); ?>
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "add_room_list")) {
  $updateSQL = sprintf("UPDATE room SET item_1=%s, item_2=%s, item_3=%s WHERE room_id=%s",
                       GetSQLValueString($_POST['item_1'], "text"),
                       GetSQLValueString($_POST['item_2'], "text"),
                       GetSQLValueString($_POST['item_3'], "text"),
                       GetSQLValueString($_POST['room_id'], "text"));

  mysql_select_db($database_asset_management_system, $asset_management_system);
  $Result1 = mysql_query($updateSQL, $asset_management_system) or die(mysql_error());

  $updateGoTo = "a_room_equipment_list.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_update_record = "-1";
if (isset($_GET['room_id'])) {
  $colname_update_record = $_GET['room_id'];
}
mysql_select_db($database_asset_management_system, $asset_management_system);
$query_update_record = sprintf("SELECT * FROM room WHERE room_id = %s", GetSQLValueString($colname_update_record, "text"));
$update_record = mysql_query($query_update_record, $asset_management_system) or die(mysql_error());
$row_update_record = mysql_fetch_assoc($update_record);
$totalRows_update_record = mysql_num_rows($update_record);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Update Room Equipment</title>
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
	color: #FFFFFF;
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
	color: #000000;
}
<!--#rmenu -->
ul#rmenu, .rsub-menu
{
	list-style-type:none;
}

ul#rmenu li
{
	width:125px;
	text-align: center;
	position :relative;
	float:right;
	margin-right:4px;
}

ul#rmenu a
{
	text-decoration : none;
	display: block;
	width: 125px;
	height: 50px;
	line-height: 25px;
	background-color: #FFF;
	border : 1px solid  #000;
}
	
ul#rmenu .rsub-menu a
{
	 margin-top : 0px;
	
}

ul#rmenu li:hover > a 
{
	background-color:#69F0BF;
}

ul#rmenu li:hover a:hover
{
	background-color:#FF0;
}

ul#rmenu ul.sub-menu
{
	display:none;
	position:absolute;
	top: 51px;
	left: 0px;
	
}

ul#rmenu li:hover .rsub-menu
{
	display:block;
}
/* Menu */

ul#rmenu, .rsub-menu
{
	list-style-type: none;
	position: absolute;
	left: 1017px;
	top: 113px;
	width: 147px;
}

ul#rmenu li
{
	width: auto;
	text-align: center;
	position : relative;
	float: right;	
}

ul#rmenu a
{
	text-decoration : none;
	display: block;
	width: 140px;
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
	background-color:#69F0BF;
}

ul#rmenu li:hover a:hover
{
	background-color: #FFFF00;
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
	margin-left: 875px;
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
	left: 328px;
	width: 160px;
	top: 291px;
	font: bold;
	background-color: #4CBB96;
	color: #FFF;
}
.table {
	background-color: #306358;
	margin-left: 140px;
	border: void;
	font-size: medium;
	font-weight: bold;
	padding-top: 20px;
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
        
	  
		<li><a href="a_real_time_view.php">Borrow Equipment</a></li>
		<li><a href="a_room_equipment_list.php">Room Equipment List</a></li>
       
          <li><a href="<?php echo $logoutAction ?>">Logout</a></li>
		
	</ul>
		<p>&nbsp;</p>
        
	
	<!-- end #menu -->
	
</div>

<div align="left" id="page">

    <p>&nbsp;</p>
    <h1> UPDATE ROOM EQUIPMENT</h1>

<form action="<?php echo $editFormAction; ?>" id="add_room_list" name="add_room_list" method="POST">

  <table width="620" class="table"><div align="left">
  
        <tr>
         <td width="148">ROOM NAME</td>
         <td><?php echo $row_update_record['room_id']; ?></td>
        </tr>
        
        <tr>
          <td>ITEM NEEDED 1</td>
          <td><label for="item_1"></label>
            <span id="sprytextfield2">
            <input name="item_1" type="text" id="item_1" value="<?php echo $row_update_record['item_1']; ?>" />
            <span class="textfieldRequiredMsg">An Item Name is Required.</span></span></td>
        </tr>
        
        <tr>
          <td>ITEM NEEDED 2</td>
          <td><label for="item_2"></label>
          <input name="item_2" type="text" id="item_2" value="<?php echo $row_update_record['item_2']; ?>" /></td>
        </tr>
        
        <tr>
            <td>ITEM NEEDED 3</td>
         
            <td> <label for="item_3"></label>
              <input name="item_3" type="text" id="item_3" value="<?php echo $row_update_record['item_3']; ?>" /></td>
          </tr>
          </div>
</table>

  <p>
    <input name="submit" type="submit" class="submit" id="submit" value= "Update Room Equipment"/>
<p>
  <input type="hidden" name="MM_update" value="add_room_list" />
  <input type="hidden" name="room_id" value="<?php echo $row_update_record['room_id']; ?>" />
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
  <div style="clear: both;">&nbsp;</div>
</div>
<!-- end #page -->
</div>
<!--  #rmenu --><!-- end #rmenu -->
<div id="footer">
	<p>Copyright (c) IT Asset Management System. All rights reserved.. </p>
	<hr />
	<p>&nbsp;</p>
</div>

<!-- end #footer -->
<div align=center></div>
<script type="text/javascript">
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
</script>
</body>
</html>
<?php
mysql_free_result($update_record);
?>
