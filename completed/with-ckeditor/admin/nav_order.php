<?php require_once('../Connections/conndb.php'); ?>
<?php
session_start();
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";
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
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "frm_insert")) {
	
	$order = $_POST['pg_order'];
	
	foreach($order as $key => $value) {
		
		$updateSQL = sprintf("UPDATE tbl_pages SET pg_order=%s WHERE pg_id=%s",
						   GetSQLValueString($value, "int"),
						   GetSQLValueString($key, "int"));

	  mysql_select_db($database_conndb, $conndb);
	  $Result1 = mysql_query($updateSQL, $conndb) or die(mysql_error());
		
	}
	
  $updateGoTo = "nav_order.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_conndb, $conndb);
$query_rsNav = "SELECT pg_id, pg_link, pg_order, tbl_nav.nav_name FROM tbl_pages, tbl_nav WHERE tbl_pages.pg_nav =tbl_nav.nav_id  ORDER BY pg_nav, tbl_pages.pg_order ASC";
$rsNav = mysql_query($query_rsNav, $conndb) or die(mysql_error());
$row_rsNav = mysql_fetch_assoc($rsNav);
$totalRows_rsNav = mysql_num_rows($rsNav);
?>
<?php require_once('inc/fnc.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tmp_admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Content Management System</title>
<!-- InstanceEndEditable -->
<link href="style/general.css" rel="stylesheet" type="text/css" />
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>
<body>
<div id="hd">
  <div id="hdin">
    <h1 id="h1">Content Management System</h1>
  </div>
</div>
<div id="nv">
  <ul id="mnv">
    <li><a href="../admin/pages_list.php"<?php getSelect('pages_list.php'); ?>>list of pages</a></li>
    <li><a href="../admin/pages_add.php"<?php getSelect('pages_add.php'); ?>>create new page</a></li>
    <li><a href="../admin/nav_order.php"<?php getSelect('nav_order.php'); ?>>navigation</a></li>
    <li><a href="../admin/email_password.php"<?php getSelect('email_password.php'); ?>>email and password</a></li>
    <li><a href="logout.php">logout</a></li>
  </ul>
</div>
<div id="wr">
  <div id="cnt"><!-- InstanceBeginEditable name="tmp_edit_title" -->
    <p class="pt">Navigation order</p>
    <form id="frm_insert" name="frm_insert" method="POST" action="<?php echo $editFormAction; ?>">
      <table border="0" cellpadding="0" cellspacing="0" id="tbl_repeat">
        <tr>
          <th>Link name</th>
          <th style="width:15%">Navigation</th>
          <th style="width:10%">Order</th>
        </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_rsNav['pg_link']; ?></td>
            <td><?php echo $row_rsNav['nav_name']; ?></td>
            <td><input type="text" name="pg_order[<?php echo $row_rsNav['pg_id']; ?>]" class="frmord" value="<?php echo $row_rsNav['pg_order']; ?>" /></td>
          </tr>
          <?php } while ($row_rsNav = mysql_fetch_assoc($rsNav)); ?>
        <tr>
          <th colspan="3"><input type="submit" name="btn" value="Update" /></th>
        </tr>
      </table>
      <input type="hidden" name="MM_update" value="frm_insert" />
    </form>    
    <!-- InstanceEndEditable -->
    <div id="ft">
      <p>&#169; <a href="http://www.sebastiansulinski.co.uk/" target="_blank" title="Web Design Tutorials">Web Design Tutorials</a> : Sebastian Sulinski</p>
    </div>
  </div>
</div>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsNav);
?>
