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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE item SET item_name=%s, item_type=%s, item_description=%s, item_status=%s, image_attachment=%s WHERE item_id=%s",
                       GetSQLValueString($_POST['item_name'], "text"),
                       GetSQLValueString($_POST['item_type'], "text"),
                       GetSQLValueString($_POST['item_description'], "text"),
                       GetSQLValueString($_POST['item_status'], "text"),
                       GetSQLValueString($_POST['image_attachment'], "text"),
                       GetSQLValueString($_POST['image_attachment'], "text"));

  mysql_select_db($database_asset_management_system, $asset_management_system);
  $Result1 = mysql_query($updateSQL, $asset_management_system) or die(mysql_error());

  $updateGoTo = "stock_control.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_update_item = "-1";
if (isset($_GET['item_id'])) {
  $colname_update_item = $_GET['item_id'];
}
mysql_select_db($database_asset_management_system, $asset_management_system);
$query_update_item = sprintf("SELECT * FROM item WHERE item_id = %s", GetSQLValueString($colname_update_item, "int"));
$update_item = mysql_query($query_update_item, $asset_management_system) or die(mysql_error());
$row_update_item = mysql_fetch_assoc($update_item);
$totalRows_update_item = mysql_num_rows($update_item);

$colname_update_item = "-1";
if (isset($_GET['item_id'])) {
  $colname_update_item = $_GET['item_id'];
}
mysql_select_db($database_asset_management_system, $asset_management_system);
$query_update_item = sprintf("SELECT * FROM item WHERE item_id = %s", GetSQLValueString($colname_update_item, "int"));
$update_item = mysql_query($query_update_item, $asset_management_system) or die(mysql_error());
$row_update_item = mysql_fetch_assoc($update_item);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Update Item</title>
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
/*Right Menu */

ul#rmenu, .rsub-menu
{
	list-style-type: none;
	position: absolute;
	left: 1005px;
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
	margin-left: 839px;
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
	width: 1050px;
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
	color: #906;
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
	background-color: #906;
	color: #FFF;
	font: bold;
	position: absolute;
	top: 407px;
	width: 100px;
	left: 586px;
}
.table {
	font-size: medium;
	font-weight: normal;
	margin-left: 100px;
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
		  <li><a>About us</a>
            <ul class="sub-menu">
            <li><a href="a_mission.php">Mision</a></li>
            <li><a href="a_vision.php">Vision</a></li>
            <li><a href="a_org_chart.php">Organization Chart</a></li>
            <li><a href="a_contact_us.php">Contact Us</a></li>
            
            </ul>
          </li>
			
		<li><a>New Equipment</a>
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
    <h1> UPDATE ITEM</h1>

  
  <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2" class="table" >
    <table align="center" class="table">
      <tr valign="baseline">
        <td width="147" align="right" nowrap="nowrap"><div align="left"><strong>Item ID</strong></div></td>
        <td width="192"><div align="left"><?php echo $row_update_item['item_id']; ?></div></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right"><div align="left"><strong>Item Name</strong></div></td>
        <td><div align="left"><span id="sprytextfield1">
          <input type="text" name="item_name" value="<?php echo htmlentities($row_update_item['item_name'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
          <span class="textfieldRequiredMsg">A name is required.</span></span></div></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right"><div align="left"><strong>Item Type</strong></div></td>
        <td><select name="item_type" id="item_type" title="<?php echo $row_update_item['item_type']; ?>">
               <option><?php echo $row_update_item['item_type']; ?></option>
              	<option> KEY</option>
                <option> PROJECTOR</option>
                <option> PROJECTOR REMOTE</option>
                <option> LAPTOP</option>
                <option> MONITOR</option>
                <option> CABLE</option>
                <option>OTHER</option>
          </select></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right"><div align="left"><strong>Item Description</strong></div></td>
        <td><div align="left"><span id="sprytextfield2">
          <input type="text" name="item_description" value="<?php echo htmlentities($row_update_item['item_description'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
          <span class="textfieldRequiredMsg">A description is required.</span></span></div></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right"><div align="left"><strong>Item Status</strong></div></td>
        <td><select name="item_status" id="item_status" title="<?php echo $row_update_item['item_status']; ?>">
             <option> <?php echo $row_update_item['item_status']; ?></option>
             <option>NEW</option>
                <option>AVAILABLE</option>
                <option>UNAVAILABLE</option>
                <option>BOOKED</option>
                <option>DAMANGED</option>
                <option>DISPOSED</option>
          </select></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right"><div align="left"><strong>Image Attachment</strong></div></td>
        <td><div align="left">
        <input name="image_attachment" value="<?php echo $row_update_item['image_attachment']?>" size="32" />
          <input type="file" name="image_attachment" value="<?php echo $row_update_item['image_attachment']?>" size="32" />
        </div></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right"><div align="left"></div></td>
        <td><div align="left">
          <input type="submit" class="submit" value="Update record" />
        </div></td>
      </tr>
    </table>
    <p>
      <input type="hidden" name="MM_update" value="form2" />
      <input type="hidden" name="item_id" value="<?php echo $row_update_item['item_id']; ?>" />
    </p>
  </form>
  <p>&nbsp;</p>
  <p>  
<p>&nbsp;</p>
  <p>&nbsp;</p>
  <div style="clear: both;">&nbsp;</div>
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
	<p>Copyright (c) Computer Operations and Services U<span class="footer">nit. All rights reserved</span>. </p>
	<hr />
	<p>&nbsp;</p>
</div>
</option>
<!-- end #footer -->
<div align=center></div>
<p>&nbsp;</p>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
</script>
</body>
</html>
<?php
mysql_free_result($update_item);
?>
