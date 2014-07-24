<?php
require("library.php")
?>
<?php
$val = "ok";
if(isset($_GET['id'])) {
	$db = new dbConnect();
	$db->deleteItem($_GET['id']);
}
header("Location: sampleView.php");
?>