<?php
/**
 * Logout Page
 */

require("library.php");

$session = new Session();

if(!$session->sessionActive()) {
	// User was not logged or was timed out, redirect to login
	//addError("Session is not Active");
	redirect("login.php");
}

$session->logOut();
redirect("login.php");
?>