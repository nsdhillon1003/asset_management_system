
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

mysql_select_db($database_asset_management_system, $asset_management_system);
$query_select_return = "SELECT borrow.staff_id, staff.staff_email ,staff.staff_name, borrow.item_id, item.item_name, borrow.borrow_timestamp, borrow.return_timestamp FROM borrow, staff, item WHERE staff.staff_id = borrow.staff_id  AND borrow.item_id = item.item_id AND borrow.return_timestamp IS NULL";
$select_return = mysql_query($query_select_return, $asset_management_system) or die(mysql_error());
$row_select_return = mysql_fetch_assoc($select_return);
$totalRows_select_return = mysql_num_rows($select_return);


$Result_0=mysql_query("SELECT * FROM borrow, staff, item WHERE staff.staff_id = borrow.staff_id AND borrow.item_id = item.item_id AND (borrow.return_timestamp IS NULL AND (borrow.borrow_timestamp + INTERVAL '6' HOUR) < CURRENT_TIMESTAMP) AND (borrow.sent_timestamp IS NULL or (borrow.sent_timestamp + INTERVAL '6' HOUR) < CURRENT_TIMESTAMP)");

if (mysql_num_rows($Result_0) >0 ) {
    // output data of each row
	
	while($row = mysql_fetch_array($Result_0)) {

$from="cosu.assetmanagementsystem@gmail.com";
$email=$row['staff_email'];
$borrow_id=$row['borrow_id'];
$count=$row['count'];
$subject="Asset Management System-ITEM NOT RETURNED!";

$message="You have not returned the item: ".$row['item_id'] .", ".$row['item_name']. " borrowed at: " .$row['borrow_timestamp'];
++$count;

$updateSQL = mysql_query("UPDATE BORROW SET SENT_TIMESTAMP=CURRENT_TIME,COUNT=$count WHERE BORROW_ID=$borrow_id");

mail ( $email, $subject, $message, "From:".$from);
	}
} else {
    echo "0 results";
}

mysql_free_result($select_return);
?>

