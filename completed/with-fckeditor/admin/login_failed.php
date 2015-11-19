<?php require_once('inc/fnc.php'); ?>
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
    <p class="pt">Login failed</p>
    <p>Your username and/or password were incorrect.<br />
      Please <a href="javascript:history.go(-1)">go back</a> and try again.</p>
    <!-- InstanceEndEditable -->
    <div id="ft">
      <p>&#169; <a href="http://www.sebastiansulinski.co.uk/" target="_blank" title="Web Design Tutorials">Web Design Tutorials</a> : Sebastian Sulinski</p>
    </div>
  </div>
</div>
</body>
<!-- InstanceEnd --></html>
