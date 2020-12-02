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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $selectSQL1 = sprintf("SELECT * FROM BORROW WHERE STAFF_ID=%s AND ITEM_ID=%s AND RETURN_TIMESTAMP IS NULL LIMIT 1",
                       GetSQLValueString($_POST['staff_id'], "text"),
                       GetSQLValueString($_POST['item_id'], "int"));
  
  $insertSQL = sprintf("INSERT INTO BORROW (staff_id, item_id) VALUES (%s, %s)",
                       GetSQLValueString($_POST['staff_id'], "text"),
                       GetSQLValueString($_POST['item_id'], "int"));

  $updateSQL2 = sprintf("UPDATE ITEM SET ITEM_STATUS='BORROWED' WHERE item_id=%s",
                       GetSQLValueString($_POST['item_id'], "int"));
				   
  mysqli_select_db( $asset_management_system, $database_asset_management_system);
  
  $Result_0 = mysqli_query($asset_management_system,$selectSQL1) or die(mysql_error());
  $row_Result_0 = mysqli_fetch_assoc($Result_0);
  $borrow_id=$row_Result_0['borrow_id'];
  $item_id=$row_Result_0['item_id'];
  
  $updateSQL = sprintf("UPDATE BORROW SET RETURN_TIMESTAMP=CURRENT_TIME WHERE staff_id=%s and item_id=%s AND BORROW_ID=$borrow_id",
                       GetSQLValueString($_POST['staff_id'], "text"),
                       GetSQLValueString($_POST['item_id'], "int"));
  $updateSQL3 = sprintf("UPDATE ITEM SET ITEM_STATUS='AVAILABLE' WHERE item_id=$item_id",
                       GetSQLValueString($_POST['item_id'], "int"));
					   
					   
  if ((mysqli_num_rows($Result_0) > 0)){
	$Result1 = mysqli_query($asset_management_system,$updateSQL) or die(mysql_error());
	# Query to update as AVAILABLE
	$Result2 = mysqli_query($asset_management_system,$updateSQL3) or die(mysql_error());
  } else {
	  $Result1 = mysqli_query($asset_management_system,$insertSQL) or die(mysql_error());
	  # Query to update as BORROWED
	  $Result3 = mysqli_query($asset_management_system,$updateSQL2) or die(mysql_error());
  }
}

mysqli_select_db($asset_management_system, $database_asset_management_system);
$query_real_time_view = "SELECT borrow.staff_id ,staff.staff_name, borrow.item_id, item.item_name, borrow.borrow_timestamp, borrow.return_timestamp  FROM borrow, item, staff  WHERE staff.staff_id = borrow.staff_id AND  item.item_id = borrow.item_id ORDER BY borrow.borrow_timestamp ASC";
$real_time_view = mysqli_query($asset_management_system,$query_real_time_view) or die(mysql_error());
$row_real_time_view = mysqli_fetch_assoc($real_time_view);
$totalRows_real_time_view = mysqli_num_rows($real_time_view);

mysqli_select_db($asset_management_system,$database_asset_management_system);
$query_select_record = "SELECT staff_id, item_id, borrow_timestamp, return_timestamp FROM borrow";
$select_record = mysqli_query($asset_management_system,$query_select_record) or die(mysqli_error());
$row_select_record = mysqli_fetch_assoc($select_record);
$totalRows_select_record = mysqli_num_rows($select_record);
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
	color: #000000;
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
	width: 115n0px;
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
#submit {
	background-color: #4CBB96;
	color: #FFF;
	font: bold;
	position: absolute;
	left: 574px;
	top: 145px;
	width: 100px;
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

	<!-- end #search -->
	
<table width="950" border="1" cellpadding="1" cellspacing="1" class="table" bgcolor="#CCFFCC">
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
    <?php } while ($row_real_time_view = mysqli_fetch_assoc($real_time_view)); ?>
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
<p>Copyright (c) IT Asset Management System. All rights reserved.</p>  <hr />
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
mysqli_free_result($real_time_view);

mysqli_free_result($select_record);
?>
