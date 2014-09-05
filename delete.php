<?php
require("library.php")

if(isset($_GET['id']) && isset($_GET['deleted'])) {
	$_GET['deleted'] == 'y' ? $changeTo = 'n' : $changeTo = 'y';
	$db = new dbConnect();
	$db->deleteItem($_GET['id'], "inventory", $changeTo);
}

redirect("sampleView.php");
?>