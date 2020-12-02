<?php @session_start();?>
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

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="add_item.php";
  $loginUsername = $_POST['item_id'];
  $LoginRS__query = sprintf("SELECT item_id FROM item WHERE item_id=%s", GetSQLValueString($loginUsername, "int"));
  mysql_select_db($database_asset_management_system, $asset_management_system);
  $LoginRS=mysql_query($LoginRS__query, $asset_management_system) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."item_id_duplicate=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "add item")) {
  $insertSQL = sprintf("INSERT INTO item (item_id, item_name, item_type, item_description, item_status, image_attachment) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['item_id'], "int"),
                       GetSQLValueString($_POST['item_name'], "text"),
                       GetSQLValueString($_POST['item_type'], "text"),
                       GetSQLValueString($_POST['item_description'], "text"),
                       GetSQLValueString($_POST['item_status'], "text"),
                       GetSQLValueString($_POST['image_attachment'], "text"));

  mysql_select_db($database_asset_management_system, $asset_management_system);
  $Result1 = mysql_query($insertSQL, $asset_management_system) or die(mysql_error());

  $insertGoTo = "added_item_page.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_asset_management_system, $asset_management_system);
$query_additem = "SELECT * FROM item";
$additem = mysql_query($query_additem, $asset_management_system) or die(mysql_error());
$row_additem = mysql_fetch_assoc($additem);
$totalRows_additem = mysql_num_rows($additem);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Add Item</title>
<style type= "text/css">

/*styletest.css*/

body {
	background-color: #306358;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 16px;
	color: #000000;
	display: block;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
}

h1, h2, h3 {
	margin: 0;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-weight: normal;
	color: #69D5AE;
}

h1 {
	font-size: 44px;
	margin-left: 45px;
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

blockquote {
	margin-left: 20px;
}

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
	background-color:#69F0BF;
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
	background-color:#4CBB96;
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
	left: 1005px;
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
	width: 170px;
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
	margin-left: 845px;
}


#rmenu .current_page_item a {
	padding-left: 0px;
	color: #FFFFFF;
}
	<!-- end #rmenu -->




/* Page */

#page {
	width: 1150px;
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

blockquote table {
	font-size: 14px;
}
.link {
	color: #000;
	}
.submit {
	background-color: #4CBB96;
	color: #FFF;
	font: bold;
	position: absolute;
	left: 595px;
	top: 375px;
	width: 100px;
}
.table {
	background-color: #306358;
	margin-left: 100px;
	border: void;
	font-size: medium;
	font-weight: bold;
}

</style>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
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
    <h1> ADD ITEM</h1>

<form id="add item" name="add item" method="POST" action="<?php echo $editFormAction; ?>">
 

      <tr>
        <td width="411">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  </table>
  
  <table width="888" class="table">
  
        <tr>
            <td width="153"><div align="left">ITEM ID</div></td>
            <td width="723"><span id="sprytextfield2">
            <input type="text" name="item_id" id="item_id" />
            <span class="textfieldRequiredMsg">An ID is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
          </tr>
        <tr>
            <td width="153"><div align="left">ITEM NAME</div></td>
            <td width="723"><span id="sprytextfield1"><span id="sprytextfield3">
              <input type="text" name="item_name" id="item_name" />
            <span class="textfieldRequiredMsg">A value is required.</span></span><span class="textfieldRequiredMsg">An Item Name is required.</span></span></td>
          </tr>
          <tr>
            <td><div align="left">ITEM TYPE</div></td>
            <td><span id="spryselect1">
              <select name="item_type" id="item_type">
                <option selected="selected">--SELECT ITEM TYPE--</option>
                <option> KEY</option>
                <option> PROJECTOR</option>
                <option> PROJECTOR REMOTE</option>
                <option> LAPTOP</option>
                <option> MONITOR</option>
                <option> CABLE</option>
                <option>OTHER</option>
              </select>
            <span class="selectInvalidMsg">Please select a valid item.</span></span></td>
          </tr>
          <tr>
            <td><div align="left">ITEM STATUS</div></td>
            <td>
              <select name="item_status" id="item_status">
                <option selected="selected"> NEW</option>
                <option> AVAILABLE</option>
                <option> UNAVAILABLE</option>
                <option> BOOKED</option>
                <option> DAMAGED</option>
                <option> DISPOSED</option>
              </select>
           </td>
          </tr>
          <tr>
            <td><div align="left">ITEM DESCRIPTION</div></td>
            <td class="table"><span id="sprytextarea1">
              <textarea name="item_description" cols="50" rows="2" id="item_description"></textarea>
            <span class="textareaRequiredMsg">A value is required.</span></span></td>
          </tr>
           
        <tr>
            <td><div align="left">IMAGE ATTACHMENT</div></td>
          <td><input name="image_attachment" type="file" /></td>
  </tr>
</table>
  <p>
    <input name="submit" type="submit" class="submit" id="submit" value= "Add Item"/>
<p>
  <input type="hidden" name="MM_insert" value="add item" />
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
  <div style="clear: both;">&nbsp;</div>
</div>
<!-- end #page -->
</div>
<!--  #rmenu -->
 <ul id="rmenu" name="rmenu">
  
		  <li><a href="#">Staff History</a></li>
		  <li><a href="#">Item</a>
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
	<p>Copyright (c) IT Asset Management System. All rights reserved.</p>
	<hr />
	<p>&nbsp;</p>
</div>

<!-- end #footer -->
<div align=center></div>
<script type="text/javascript">
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "integer");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1", {isRequired:false, validateOn:["change"], invalidValue:"--SELECT ITEM TYPE--"});
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
</script>
</body>
</html>
<?php
mysql_free_result($additem);
?>
