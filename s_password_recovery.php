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
	width: 1150px;
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
	background-color: #4CBB96;
	color: #FFF;
}
.submit1 {
	background-color: #4CBB96;
	color: #FFF;
	position: absolute;
	left: 345px;
	top: 365px;
	width: 120px;
}
#staff_login table tr td {
	font-weight: bold;
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
	
</div>

<div id="page">

      <p>&nbsp;</p>
      <p>
    <h1>Staff Login- Forgot Password.    </h1>
    <form action="EMPW-Script.php" method="post" class="table" id="EMPWForm">
      <p>Please insert your email address below so that we can send your password to you as soon as possible in 
      your email.</p>
      <p></p>
      <table width="319">
        <tr>
          <td width="116"><b>Email Address</b></td>
          <td width="191"><label for="email"></label>
            <span id="sprytextfield1">
             <b>:</b> 
            <input type="text" name="email" id="email" />
          <span class="textfieldRequiredMsg">A value is required.</span></span></td>
        </tr>
      </table>
      <p>
        <input name="submit" type="submit" class="submit1" id="submit" value="Email Password" />
      </p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    </form>
    <p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <div style="clear: both;">&nbsp;</div>
</div>
<!-- end #page -->
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
</script>
</body>
</html>