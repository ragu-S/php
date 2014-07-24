<?php
/**
 * Created by Ragu S.
 * Date: 07/23/2014
 * Time: 12:46 pm
 * for coding standards follow PEAR, see sample file on the site
 */

require("library.php");

$html = new html();
$html->htmlHead();
$html->htmlBody();
$form = new FormItemEntry();

if($_POST || $_GET) {
    if($form->validateForm()) { 
        //$data = new dbConnect();
        //$data->insertItems($_POST);
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
				<textarea name="description" value="<?= $form->displayItem('description'); ?>">
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
				<input type="text" name="sellingPrice" value="<?= $form->displayItem('sellingPrice'); ?>" />
			</td>
			<?= $form->showFormErrors("sellingPrice");?>
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
				<input type="checkbox" name="backOrder" value="1" <?= $form->displayItem('backOrder'); ?> />
			</td>
			<?= $form->showFormErrors("backOrder");?>
		</tr>
		<tr>
			<td>
				<input type="submit" name="submit"/>
			</td>
			<td>
				<input type="reset" value="Clear" />
			</td>
		</tr>
	</table>
</form>
<?php
$html->htmlFooter();
?>