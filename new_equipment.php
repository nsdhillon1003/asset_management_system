
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

mysqli_select_db($asset_management_system,$database_asset_management_system);
$query_new_equipment = "SELECT * FROM item WHERE item_status = 'NEW'";
$new_equipment = mysqli_query($asset_management_system,$query_new_equipment) or die(mysqli_error());
$row_new_equipment = mysqli_fetch_assoc($new_equipment);
$totalRows_new_equipment = mysqli_num_rows($new_equipment);
?>

<?php

$printing= '';
$output='';

if(isset($_POST['search'])) {
	$searchq= $_POST['search'];
	$searchq= preg_replace("#[^0-9a-z]#i","",$searchq);
	
	$query= mysqli_query("SELECT * FROM item WHERE item_id LIKE '%$searchq%' OR item_name LIKE '%$searchq%' OR item_type LIKE '%$searchq%' OR item_status LIKE '%$searchq%'") or die ("Could not search!");
	
	$count= mysqli_num_rows($query);
	if ($count==0){
		$output= 'There is no search results';
	}else{
		
		while($row= mysql_fetch_array($query)){
		$id=$row['item_id'];
		$name=$row['item_name'];
		$type=$row['item_type'];
		$status=$row['item_status'];

	
	$printing = 'Search Results';}
		
		}
		
		}

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>New Equipment</title>
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
	width: 1150px;
	padding: 0px 0px 0px 0px;
	background-repeat: no-repeat;
	top: auto;
	background-position-x: center;
	background-position-y: top;
	padding-left: 100px;
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
	color: #FFF;
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

.table {
	font-size: 16px;
}
.empty {
}
</style>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
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
		<div id="search">
	  <form method="get" action="new_equipment.php">
		  <span id="sprytextfield1">
		  <fieldset>
			<input type="text" name="s" id="search-text" size="15" />
			<input type="submit" id="search-submit" value="Search" />
		</fieldset>
	  </form>
	</div>
	<!-- end #search -->

<div id="page">
<!--printing search result-->

  <h2><?php print("$printing");?></h2>

<?php

@$count= mysqli_num_rows($query);
	if ($count==!0)
	
 do { ?>
	   <blockquote>
      <blockquote>&nbsp;</blockquote>
</blockquote>
  <div style="clear: both; alignment-adjust: hanging;">
    <blockquote>
      <blockquote>
<table width="950" border="1" cellpadding="1" cellspacing="1" class="table" bgcolor="#CCFFCC">
<tr>
<td>
<font face="Arial, Helvetica, sans-serif"><b>Item ID</b></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><b>Item Name</b></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><b>Item Type</b></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><b>Item Status</b></font>
</td>

</tr>
<tr>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $id; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $name; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $type; ?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $status; ?></font>
</td>

</tr>
</table>
<?php } while($row= mysql_fetch_array($query));?>
    <p>&nbsp;</p>
    <h1>New Equipment!</p></h1>
    <p>&nbsp;</p>
<table width="950" border="1" cellpadding="1" cellspacing="1" class="table" bgcolor="#CCFFCC">
      <tr>
        <td width="205"><div align="center"><b>Item Name</b></div></td>
        <td width="192"><div align="center"><b>Item Type</b></div></td>
        <td width="243"><div align="center"><b>Item Description</b></div></td>
        <td width="211"><div align="center"><b>Item Status</b></div></td>
        
        <td width="200"><div align="center"><b>Image </b></div></td>
      </tr>
      <?php do { ?>
        <tr>
          <td><?php echo $row_new_equipment['item_name']; ?></td>
          <td><?php echo $row_new_equipment['item_type']; ?></td>
          <td><?php echo $row_new_equipment['item_description']; ?></td>
          <td><?php echo $row_new_equipment['item_status']; ?></td>
          
          <td><img src="<?php echo $row_new_equipment['image_attachment']; ?>" width="200" height="125" alt="Item Image" /></td>
        </tr>
        <?php } while ($row_new_equipment = mysqli_fetch_assoc($new_equipment)); ?>
    </table>
<p>&nbsp;</p>
    <p>&nbsp;</p>
		<p>&nbsp;</p>
 </blockquote/>
<!-- end #page -->
</div>
<div id="footer">
  <hr />
<p>Copyright (c) IT Asset Management System. All rights reserved.</p>  <hr />
  <p>&nbsp;</p>
</div>
<!-- end #footer -->
<div align=center></div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
</script>
</body>
</html>
<?php
mysqli_free_result($new_equipment);
?>
