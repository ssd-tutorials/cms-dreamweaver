<?php include_once('fckeditor/fckeditor.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
	mysql_select_db($database_conndb, $conndb);
	$query_rsCount = "SELECT pg_id FROM tbl_pages WHERE pg_nav = ".$_POST['pg_nav']."";
	$rsCount = mysql_query($query_rsCount, $conndb) or die(mysql_error());
	$row_rsCount = mysql_fetch_assoc($rsCount);
	$totalRows_rsCount = mysql_num_rows($rsCount);

	$nextnmb = ($totalRows_rsCount + 1);
	mysql_free_result($rsCount);
	
if ($_POST['pg_link'] == '' || $_POST['pg_title'] == '') {

	if ($_POST['pg_link'] == '') {
	
		$pg_link = 'Please provide the link name';
	
	}
	
	if ($_POST['pg_title'] == '') {
	
		$pg_title = 'Please provide the heading';
	
	}

} else {

$sValue = stripslashes( $_POST['FCKeditor1'] ) ;

  $insertSQL = sprintf("INSERT INTO tbl_pages (pg_link, pg_title, pg_cont, pg_nav, pg_order) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['pg_link'], "text"),
                       GetSQLValueString($_POST['pg_title'], "text"),
                       GetSQLValueString($sValue, "text"),
                       GetSQLValueString($_POST['pg_nav'], "int"),
					   GetSQLValueString($nextnmb, "int"));

  mysql_select_db($database_conndb, $conndb);
  $Result1 = mysql_query($insertSQL, $conndb) or die(mysql_error());

  $insertGoTo = "pages_added.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

}

mysql_select_db($database_conndb, $conndb);
$query_rsNav = "SELECT * FROM tbl_nav ORDER BY nav_id ASC";
$rsNav = mysql_query($query_rsNav, $conndb) or die(mysql_error());
$row_rsNav = mysql_fetch_assoc($rsNav);
$totalRows_rsNav = mysql_num_rows($rsNav);
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
    <p class="pt">Create new page</p>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table border="0" cellpadding="0" cellspacing="0" id="tbl_insert">
      <?php if (isset($pg_link)) { ?>
          <tr valign="baseline">
          	<th>&nbsp;</th>
            <td class="wrn"><?php echo $pg_link; ?></td>
          </tr>
          <?php } ?>
        <tr valign="baseline">
          <th><label for="pg_link">Link name:</label></th>
          <td><input name="pg_link" id="pg_link" type="text" class="frmfld" value="<?php if(isset($_POST['pg_link'])) { echo $_POST['pg_link']; } ?>" size="32" /></td>
        </tr>
        <?php if (isset($pg_title)) { ?>
          <tr valign="baseline">
          	<th>&nbsp;</th>
            <td class="wrn"><?php echo $pg_title; ?></td>
          </tr>
          <?php } ?>
        <tr valign="baseline">
          <th><label for="pg_title">Heading:</label></th>
          <td><input name="pg_title" id="pg_title" type="text" class="frmfld" value="<?php if(isset($_POST['pg_title'])) { echo $_POST['pg_title']; } ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
         <td style="width:800px;" colspan="2"><?php
				$oFCKeditor = new FCKeditor('FCKeditor1');
				$oFCKeditor->BasePath = 'fckeditor/';
				$oFCKeditor->Value = $row_rsPage['pg_cont'];
				$oFCKeditor->Create();
			  ?>
          </td>
        </tr>
        <tr valign="baseline">
          <th><label for="pg_nav">Navigation:</label></th>
          <td><select name="pg_nav" id="pg_nav">
              <?php 
do {  
?>
              <option value="<?php echo $row_rsNav['nav_id']?>"<?php if (isset($_POST['pg_nav']) && $_POST['pg_nav'] == $row_rsNav['nav_id']) { ?> selected="selected"<?php } ?>><?php echo $row_rsNav['nav_name']?></option>
              <?php
} while ($row_rsNav = mysql_fetch_assoc($rsNav));
?>
            </select>
          </td>
        </tr>
        <tr> </tr>
        <tr valign="baseline">
          <th>&nbsp;</th>
          <td><input type="submit" value="Insert record" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form1" />
    </form>
    <p>&nbsp;</p>
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
