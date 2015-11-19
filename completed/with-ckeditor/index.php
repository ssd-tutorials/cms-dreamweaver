<?php require_once('Connections/conndb.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

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
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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

if (isset($_GET['p']) && $_GET['p'] != '') {
	$p = $_GET['p'];
} else {
	$p = 1;
}

function getSelect($v1) {
	
	global $p;

	if ($p == $v1) {
	
		echo ' class="act"';
	
	}
	
}

mysql_select_db($database_conndb, $conndb);
$query_rsTopNav = "SELECT pg_id, pg_link FROM tbl_pages WHERE tbl_pages.pg_nav=1 LIMIT 5";
$rsTopNav = mysql_query($query_rsTopNav, $conndb) or die(mysql_error());
$row_rsTopNav = mysql_fetch_assoc($rsTopNav);
$totalRows_rsTopNav = mysql_num_rows($rsTopNav);

mysql_select_db($database_conndb, $conndb);
$query_rsMainNav = "SELECT tbl_pages.pg_id, tbl_pages.pg_link FROM tbl_pages WHERE tbl_pages.pg_nav=2 ORDER BY tbl_pages.pg_order, tbl_pages.pg_link ASC LIMIT 5";
$rsMainNav = mysql_query($query_rsMainNav, $conndb) or die(mysql_error());
$row_rsMainNav = mysql_fetch_assoc($rsMainNav);
$totalRows_rsMainNav = mysql_num_rows($rsMainNav);

mysql_select_db($database_conndb, $conndb);
$query_rsNavBottom = "SELECT tbl_pages.pg_id, tbl_pages.pg_link FROM tbl_pages WHERE tbl_pages.pg_nav=3";
$rsNavBottom = mysql_query($query_rsNavBottom, $conndb) or die(mysql_error());
$row_rsNavBottom = mysql_fetch_assoc($rsNavBottom);
$totalRows_rsNavBottom = mysql_num_rows($rsNavBottom);

mysql_select_db($database_conndb, $conndb);
$query_rsContent = "SELECT tbl_pages.pg_title, tbl_pages.pg_cont FROM tbl_pages WHERE tbl_pages.pg_id = $p";
$rsContent = mysql_query($query_rsContent, $conndb) or die(mysql_error());
$row_rsContent = mysql_fetch_assoc($rsContent);
$totalRows_rsContent = mysql_num_rows($rsContent);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Content Management System</title>
<meta name="description" content="" />
<meta name="keywords" content="" />
<meta http-equiv="imagetoolbar" content="no" />
<link href="style/general.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="hd">
  <div id="hdin">
    <h1 id="h1">Business Name</h1>
    <ul id="hdnv">
      <?php if ($totalRows_rsTopNav > 0) { ?>
      <?php do { ?>
        <li><a href="?p=<?php echo $row_rsTopNav['pg_id']; ?>"><?php echo $row_rsTopNav['pg_link']; ?></a></li>
        <?php } while ($row_rsTopNav = mysql_fetch_assoc($rsTopNav)); ?>
      <?php } ?>
    </ul>
  </div>
</div>
<div id="nv">
  <ul id="mnv">
    <?php do { ?>
      <li><a href="?p=<?php echo $row_rsMainNav['pg_id']; ?>"<?php getSelect($row_rsMainNav['pg_id']); ?>><?php echo $row_rsMainNav['pg_link']; ?></a></li>
      <?php } while ($row_rsMainNav = mysql_fetch_assoc($rsMainNav)); ?>
  </ul>
</div>
<div id="wr">
  <div id="cnt">
    <p class="pt"><?php echo $row_rsContent['pg_title']; ?></p>
    <?php echo $row_rsContent['pg_cont']; ?>
    <?php if ($p == 5) { include ('inc/_contact.php'); } ?>
    <div class="cl">&#160;</div>
    <div id="ft">
      <p>
        <?php do { ?>
          <a href="?p=<?php echo $row_rsNavBottom['pg_id']; ?>"><?php echo $row_rsNavBottom['pg_link']; ?></a>
          <?php } while ($row_rsNavBottom = mysql_fetch_assoc($rsNavBottom)); ?>
        <br />
        &#169; <a href="http://www.sebastiansulinski.co.uk/" target="_blank" title="Web Design Tutorials">Web Design Tutorials</a> : Sebastian Sulinski</p>
    </div>
  </div>
</div>
</body>
</html>
<?php
mysql_free_result($rsTopNav);

mysql_free_result($rsMainNav);

mysql_free_result($rsNavBottom);

mysql_free_result($rsContent);
?>
