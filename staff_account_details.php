<?php require_once('Connections/asset_management_system.php'); ?>
<?php @session_start();?>
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

mysql_select_db($database_asset_management_system, $asset_management_system);
$query_staff_detail = "SELECT staff_id, staff_name FROM staff";
$staff_detail = mysql_query($query_staff_detail, $asset_management_system) or die(mysql_error());
$row_staff_detail = mysql_fetch_assoc($staff_detail);
$totalRows_staff_detail = mysql_num_rows($staff_detail);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Staff Account Details</title>
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
	color: #990033;
}

h1 {
	font-size: 44px;
	margin-left: 45px;
}

h2 {
	font-size: x-large;
	text-align: left;
	padding-top: 20px;
	padding-left: 40px;
}

h3 {
	color: #F00;
	font-weight: bold;
	padding-top: 10px;
}

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
	color: #FFFFFF;
}
/*Right Menu */

ul#rmenu, .rsub-menu
{
	list-style-type: none;
	position: absolute;
	left: 985px;
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
	margin-left: 818px;
}


#rmenu .current_page_item a {
	padding-left: 0px;
	color: #FFFFFF;
}
	<!-- end #rmenu -->
/* Search */

.search {
	position: absolute;
	width: 108px;
	left: 1212px;
	top: 10px;
}
.searchbutton {
	position: absolute;
	left: 1140px;
	top: 9px;
	width: 68px;
	color: #FFFFFF;
	background-color: #906;
}


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
blockquote table {
	font-size: 16px;
}
.link {
	color: #000;
}
.table {
	font-size: 16px;
	margin-left: 60px;
}
.link {
}
.search {
	position: absolute;
	width: 108px;
	left: 1212px;
	top: 10px;
}
</style>

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
    <div id="search">
    <form method="post" action="staff_account_details.php">
      <span id="sprytextfield1">
      <input name="search" type="text" class="search" id="search" size="5" />
      </span>
      <input type="submit" class="searchbutton" id="search-submit" value="Search" />
	    
  </form>
</div>
	<!-- end #search -->
	
</div>

<div align="left" id="page">
  <?php

$printing= '';
$output='';
$count=0;

if(isset($_POST['search'])) {
	$searchq= $_POST['search'];
	$searchq= preg_replace("#[^0-9a-z]#i","",$searchq);
	
	$query= mysql_query("SELECT * FROM staff WHERE staff_id LIKE '%$searchq%' OR staff_name LIKE '%$searchq%'") or die ("Could not search!");
	
	$count= mysql_num_rows($query);
	if($count==0){
		
		$output='There is no search results for: ' .$searchq;
		?>
  </span>
        
  <h3><?php print("$output");?></h3><?php
	}else{
		
		while($row= mysql_fetch_array($query)){
		$id=$row['staff_id'];
		$name=$row['staff_name'];
		

	
	$printing = 'Search Results'; ?>
   <!--printing search result-->
   <h2><?php print("$printing");?></h2></p>
<blockquote>
<blockquote>

  <table border="1" cellpadding="1" cellspacing="1" width="384" class="table">
        
        
          <tr>
            <td  height="29" align="center"><b>Staff Id</b></td>
            <td align="center"><b>Staff Name</b></td>
            
          </tr>
          <?php do { ?>
          <tr>
            <td><?php echo $row['staff_id']; ?></td>
            <td><?php echo $row['staff_name']; ?></td>
           
          </tr>
          <?php } while ($row = mysql_fetch_assoc($query)); ?>
    </table>
  <?php  }
		
		}
		
		}

?>

</blockquote>
</blockquote>

    <p>&nbsp;</p>
    <h1> Staff Account Details</h1>
    <blockquote>
      <blockquote>&nbsp;
        <table width="384" border="1" cellpadding="1" cellspacing="1"
        class="table">
          <tr>
            <td><b>Staff ID</b></td>
            <td><b>Staff Name</b></td>
          </tr>
          <?php do { ?>
            <tr>
              <td><a href="update_staff_account.php?staff_id=<?php echo $row_staff_detail['staff_id']; ?>" class="link"><?php echo $row_staff_detail['staff_id']; ?></a></td>
              <td><?php echo $row_staff_detail['staff_name']; ?></td>
            </tr>
            <?php } while ($row_staff_detail = mysql_fetch_assoc($staff_detail)); ?>
        </table>
      </blockquote>
</blockquote>
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
        
       
        
		
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>
	  
	  <!-- end #rmenu -->
</p>
<div id="footer">
	<hr />
	<p>Copyright (c) IT Asset Management System. All rights reserved.
	  </option>
	  <hr />
<!-- end #footer --></p>
</div>
<div align=center></div>
</body>
</html>
<?php
mysql_free_result($staff_detail);
?>
