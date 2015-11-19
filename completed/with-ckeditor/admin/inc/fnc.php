<?php

function getSelect($v1) {
	
	$p = basename($_SERVER['SCRIPT_NAME']);
	
	if ($p == $v1) {
	
		echo ' class="act"';
	
	}

}

?>