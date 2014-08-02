<?php
/**
 * Created by Ragu S.
 * Date: 07/23/2014
 * Time: 12:46 pm
 * for coding standards follow PEAR, see sample file on the site
 */

require("library.php");

// $session = new Session();
// if(!$session->sessionActive()) {
// 	// User was not logged or was timed out, redirect to login
// 	addError("Session is not Active");
	
// 	redirect("login.php");
// }

$html = new html();
$menu = new Menu();
$html->htmlHead();
$html->htmlBody();
$form = new FormItemEntry();
$data = new DbConnect();
if($_SERVER["REQUEST_METHOD"] == "POST") {
	print("CALLING");
    
    if($form->validateForm()) { 
    	addError("All validation passed");
        $data = new DbConnect();
        //print_r($_POST);
        //$query = $data->insertItems($_POST, "inventory");
        $query = $data->insert("inventory", $_POST, "id");
        var_dump($query);
    }
    else{
    	addError("Validation failed");
    }
}

?>

<form action="addItem.php" method="post">
	<table name="itemEntry" class="itemEntry">
		<tr>
			<td>
				Item Name:
			</td>
			<td>
				<input type="text" name="itemName" value="<?= $form->displayItem('itemName'); ?>" />
			</td>
			<?= $form->showFormErrors("itemName");?>
		</tr>
		<tr>
			<td>
				Description:
			</td>
			<td>
				<textarea name="description">
					<?= $form->displayItem('description'); ?>
				</textarea>
			</td>
			<?= $form->showFormErrors("description");?>
		</tr>
		<tr>
			<td>
				Supplier Code:
			</td>
			<td>
				<input type="text" name="supplierCode" value="<?= $form->displayItem('supplierCode'); ?>" />
			</td>
			<?= $form->showFormErrors("supplierCode");?>
		</tr>
		<tr>
			<td>
				Cost:
			</td>
			<td>
				<input type="text" name="cost" value="<?= $form->displayItem('cost'); ?>" />
			</td>
			<?= $form->showFormErrors("cost");?>
		</tr>
		<tr>
			<td>
				Selling price:
			</td>
			<td>
				<input type="text" name="price" value="<?= $form->displayItem('price'); ?>" />
			</td>
			<?= $form->showFormErrors("price");?>
		</tr>
		<tr>
			<td>
				Number on hand:
			</td>
			<td>
				<input type="text" name="onHand" value="<?= $form->displayItem('onHand'); ?>" />
			</td>
			<?= $form->showFormErrors("onHand");?>
		</tr>
		<tr>
			<td>
				Reorder Point:
			</td>
			<td>
				<input type="text" name="reorder" value="<?= $form->displayItem('reorder'); ?>" />
			</td>
			<?= $form->showFormErrors("reorder");?>			
		</tr>
		<tr>
			<td>
				On Back Order:
			</td>
			<td>
				<input type="checkbox" name="backOrder" <?= $form->displayItem('backOrder'); ?> />
			</td>
			<?= $form->showFormErrors("backOrder");?>
		</tr>
		<tr>
			<td>
				<input type="submit" />
			</td>
			<td>
				<input type="reset" value="Clear" />
			</td>
		</tr>
	</table>
</form>
<?php
displayError();
$html->htmlFooter();
?>