<?php @session_start();?>
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

mysqli_select_db($asset_management_system,$database_asset_management_system);
$query_item_details = "SELECT * FROM item";
$item_details = mysqli_query($asset_management_system,$query_item_details) or die(mysqli_error());
$row_item_details = mysqli_fetch_assoc($item_details);
$totalRows_item_details = mysqli_num_rows($item_details);
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
<title>Item Details</title>
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

ul#rmenu, .rsub-menu
{
	list-style-type: none;
	position: absolute;
	left: 1004px;
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
	margin-top: 107px;
	margin-left: 862px;
}


#rmenu .current_page_item a {
	padding-left: 0px;
	color: #000000;
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
	background-color: #FFF;
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
#wrapper #page p #button {
	text-decoration: blink;
	background-attachment: fixed;
	background-color: #fff;
	color: #FFF;
}
.submit {
	position: absolute;
	left: 63px;
	width: 93px;
	/* [disabled]top: 476px; */
}
blockquote table {
	font-size: 14px;
}
.link {
	color: #000;
}
	/* Search */

#search {
	float: right;
	width: 215px;
	height: 22px;
	margin-top: -60px;
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
          </li></ul>
		<p>&nbsp;</p>
        
	</div>
	<!-- end #menu -->
	

	<div id="search">
	  <form method="get" action="">
		  <fieldset>
			<input type="text" name="s" id="search-text" size="15" />
			<input type="submit" id="search-submit" value="Search" />
		</fieldset>
	  </form>
	</div>
	<!-- end #search -->
	
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
<div align="left" id="page">

    <p>&nbsp;</p>
    <h1> Item Details    </h1>
    <blockquote>
      <blockquote>&nbsp;</blockquote>
</blockquote>
  <div style="clear: both; alignment-adjust: hanging;">
    <blockquote>
      <blockquote>
        <table border="1" cellpadding="1" cellspacing="1" bgcolor="#CCFFCC">
        
        
          <tr>
            <td width="80" height="29" align="center"><b>Item Id</b></td>
            <td width="120" align="center"><b>Item Name</b></td>
            <td width="140" align="center"><b>Item Type</b></td>
            <td width="190" height="29" align="center"><b>Item Description</b></td>
            <td width="110" align="center"><b>Item Status</b></td>
           
                        <td width="150" height= "29" align="center"><b>Image Attachment</b></td>
          </tr>
          <?php do { ?>
          <tr>
            <td><?php echo $row_item_details['item_id']; ?></td>
            <td><?php echo $row_item_details['item_name']; ?></td>
            <td><?php echo $row_item_details['item_type']; ?></td>
            <td><?php echo $row_item_details['item_description']; ?></td>
            <td><?php echo $row_item_details['item_status']; ?></td>
           
                       <td><img src="<?php echo $row_item_details['image_attachment']; ?>" width="125" height="125" alt="item" /></td>
          </tr>
          <?php } while ($row_item_details = mysqli_fetch_assoc($item_details)); ?>
    </table>

        <p>&nbsp;</p>
      </blockquote>
    </blockquote>
  </div>
</div>
</div>
<!-- end #page -->
</div>
<!--  #rmenu --><!-- end #rmenu -->
<div id="footer">
	<hr />
<p>Copyright (c) IT Asset Management System. All rights reserved.</p>
	  </p>
	  <hr />
<!-- end #footer --></p>
</div>
<div align=center></div>
</body>
</html>
<?php
mysqli_free_result($item_details);
?>
