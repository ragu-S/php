<?php
require("library.php")
?>
<?php
$val = "ok";

if(isset($_GET['id']) && isset($_GET['deleted'])) {
	//print($_GET['id']);
	//print($_GET['deleted']);
	$_GET['deleted'] == 'y' ? $changeTo = 'n' : $changeTo = 'y';
	$db = new dbConnect();
	$db->deleteItem($_GET['id'], "inventory", $changeTo);
}
header("Location:sampleView.php");
exit();
?>