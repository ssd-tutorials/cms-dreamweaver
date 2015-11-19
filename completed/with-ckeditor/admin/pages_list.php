<?php require_once('../Connections/conndb.php'); ?>
<?php require_once('inc/fnc.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
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

mysql_select_db($database_conndb, $conndb);
if (isset($_GET['frmsch']) && $_GET['frmsch'] != '') {
	
	$srch = $_GET['frmsch'];
	
	$query_rsPages = "SELECT tbl_pages.pg_id, tbl_pages.pg_link, tbl_nav.nav_name FROM tbl_pages, tbl_nav WHERE tbl_pages.pg_nav=tbl_nav.nav_id AND (tbl_pages.pg_link LIKE '%$srch%' OR tbl_pages.pg_cont LIKE '%$srch%' OR tbl_pages.pg_title LIKE '%$srch%') ORDER BY tbl_nav.nav_id,  tbl_pages.pg_order ASC";
	
} else {
	
	$query_rsPages = "SELECT tbl_pages.pg_id, tbl_pages.pg_link, tbl_nav.nav_name FROM tbl_pages, tbl_nav WHERE tbl_pages.pg_nav=tbl_nav.nav_id ORDER BY tbl_nav.nav_id,  tbl_pages.pg_order ASC";
	
}
$rsPages = mysql_query($query_rsPages, $conndb) or die(mysql_error());
$row_rsPages = mysql_fetch_assoc($rsPages);
$totalRows_rsPages = mysql_num_rows($rsPages);

$noremove = array (1,5);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tmp_admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Content Management System</title>
<!-- InstanceEndEditable -->
<link href="style/general.css" rel="stylesheet" type="text/css" />
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
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
    <p class="pt">List of pages</p>
    <form id="frm_insert" name="frm_insert" method="get" action="pages_list.php">
      <table border="0" cellpadding="0" cellspacing="0" id="tbl_insert">
        <tr>
          <th scope="row"><label for="frmsch">Search for:</label></th>
          <td><input name="frmsch" type="text" class="frmfld" id="frmsch"<?php if(isset($_GET['frmsch'])) { echo 'value="'.$_GET['frmsch'].'"'; } ?> /></td>
          <td><input type="submit" value="Search" /></td>
        </tr>
      </table>
    </form>
    <?php if ($totalRows_rsPages > 0) { // Show if recordset not empty ?>
    <table border="0" cellpadding="0" cellspacing="0" id="tbl_repeat">
      <tr>
        <th>Page</th>
        <th>Navigation</th>
        <th style="width:10%">Remove</th>
        <th style="width:10%">Edit</th>
      </tr>
      <?php do { ?>
        <tr>
          <td><?php echo $row_rsPages['pg_link']; ?></td>
          <td><?php echo $row_rsPages['nav_name']; ?></td>
          <td><?php if (in_array($row_rsPages['pg_id'], $noremove)) { echo '&nbsp;'; } else { ?><a href="pages_remove_confirm.php?id=<?php echo $row_rsPages['pg_id']; ?>">Remove</a><?php } ?></td>
          <td><a href="pages_edit.php?id=<?php echo $row_rsPages['pg_id']; ?>">Edit</a></td>
        </tr>
        <?php } while ($row_rsPages = mysql_fetch_assoc($rsPages)); ?>
    </table>
    <?php } else { ?>
    <p>There are no records available.</p>
    <?php } ?>
    <!-- InstanceEndEditable -->
    <div id="ft">
      <p>&#169; <a href="http://www.sebastiansulinski.co.uk/" target="_blank" title="Web Design Tutorials">Web Design Tutorials</a> : Sebastian Sulinski</p>
    </div>
  </div>
</div>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsPages);
?>
