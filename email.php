<?php
require_once 'library.php';
// Check https, else redirect

$html = new html();
//$menu = new Menu();
$html->htmlHead();
//$menu->displayMenu();
//$form = new FormItemEntry();
//
//editerconfig.js => useful for jsHint (u can specify indentation, spaces, etc)

//templating language => places stuff in HTML pages 


//c:\Users\Public\JavaScript\sandbox\node_modules\generator-respec\app

?>
<div class="page">
	<div class="content">
	<header>
		<b>Email</b>
	</header>
		<form class="email">
			<input type="text" name="email" />
			<input type="submit" />
		</form>
		
	</div>
</div>
<?php
	$html->htmlFooter();
?>

