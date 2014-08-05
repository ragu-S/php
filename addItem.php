<?php
/**
 * Created by Ragu S.
 * Date: 07/23/2014
 * Time: 12:46 pm
 * for coding standards follow PEAR, see sample file on the site
 */

require("library.php");

$session = new Session();

if(!$session->sessionActive()) {
	// User was not logged or was timed out, redirect to login
	addError("Session is not Active");
	
	redirect("login.php");
}

$html = new html();
$menu = new Menu();
$html->htmlHead();
$menu->displayMenu();
$form = new FormItemEntry();
$data = new DbConnect();

if(isset($_GET['id'])) {
	addError("MODIFY CALL THROUGH GET ".$_GET['id']);
	// retrieve item record from database
	$query = "SELECT * FROM gamesite.inventory".
			 " WHERE id = ?";//.$_GET['id'];
//			 SELECT * FROM gamesite.inventory WHERE id = 0000000001
	$_GET['id'] = removeSpecialChars($_GET['id']);
	//retrieveAll
	$record = $data->retrieveSpecial($query, array($_GET['id']));
	//print_r($data->retrieveSpecial($query, array($_GET['id'])));
	if($record) {
		$_POST = $record;	
	}

	$form->validateForm(true);
	// add item Id field
		
	// repopulate with existing item's values 
}
elseif($_SERVER["REQUEST_METHOD"] == "POST") {
    if($form->validateForm()) { 
    	addError("All validation passed");
        $data = new DbConnect();

        //$query = $data->insertItems($_POST, "inventory");
        
		if(isset($_POST['id'])) {
			$db = $data->getDbName();
			$cols = $data->getColumns("inventory");
			
			$setClause = "SET ";
			$id = array_shift($_POST);
			array_shift($cols);
			foreach($cols as $colName) {
				$setClause .= "$colName = :$colName,";
			}
			$setClause = substr($setClause, 0, strrpos($setClause, ","));
			$query = "UPDATE $db.inventory ".
                     $setClause.
                     " WHERE id = $id";
			print($query);
			$data->update($_POST, $query);
		}
		else {
			$data->insert("inventory", $_POST, "id");
		}
    }
    else{
    	addError("Validation failed");
    }
}

// function modifyItem() {
	
// }

?>

<form action="addItem.php" method="post">
	<table name="itemEntry" class="itemEntry">
		<?php
			if(isset($_GET['id'])) {
		?>
		<tr>
			<td>
				Item Id:
			</td>
			<td>
				<input type="text" name="id" value="<?= $form->displayItem('id'); ?>" readonly="readonly" />
			</td>
			
		</tr>
		<?php
		}
		?>
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
				<input type="text" name="reorderPoint" value="<?= $form->displayItem('reorderPoint'); ?>" />
			</td>
			<?= $form->showFormErrors("reorderPoint");?>			
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