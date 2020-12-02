<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_asset_management_system = "localhost";
$database_asset_management_system = "asset management system";
$username_asset_management_system = "root";
$password_asset_management_system = "";
$asset_management_system = mysqli_connect($hostname_asset_management_system, $username_asset_management_system, $password_asset_management_system) or trigger_error(mysql_error(),E_USER_ERROR); 
?>