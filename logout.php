<?php
function logout() {
	$path=str_replace("logout.php","",$_SERVER['HTTP_REFERER']);
	echo '<script>var request = new XMLHttpRequest();                                        
    request.open("get", "welcome", false, "false", "false");                                                                                                                               
    request.send();
	window.location.replace("'.$path.'");</script>';
}
logout();
?>
