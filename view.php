<?php

require("library.php");	



function makeColumns(){
	//if(itemRecord->queryEntries() > 0) {

		// echo "<td>ID</td>";
		// echo "<td>Item Name</td>";
		// echo "<td>Description</td>";
		// echo "<td>Supplier</td>";
		// echo "<td>Cost</td>";
		// echo "<td>Price</td>";
		// echo "<td>Number On Hand</td>";
		// echo "<td>Reorder Level</td>";
		// echo "<td>On Back Order?</td>";
		// echo "<td>Delete/Restore</td>";
	
	$form = new formUserRegisteration(); // class must be defined inside function scope

	assertT($form->title);
	echo "<td>".$form->title."</td>";

	echo "<td>$form->firstName</td>";
	echo "<td>$form->lastName</td>";
	echo "<td>$form->organization</td>";
	echo "<td>$form->email</td>";
	echo "<td>$form->phone</td>";
	echo "<td>$form->tShirt</td>";
	
	//var_dump(readAuthenticateFile());
	// echo "<td>#</td>";
	// echo "<td>#</td>";
	
	//}
}


?>
 

<!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>View Entries</title>
        <script>
        </script>
        <style>
        	table.content_query, tr {
        		border: solid 2px;
        		border-collapse: collapse;
        	}
        	table.content_query tr:first-child td {
        		text-align: center;
        		border-right: solid 1px ;
        		padding: 5px 15px;
        		font-weight: bold; 
        		color: white;
        		background-color: black;
        	}

        </style>
    </head>
    <body>
    	<table class="content_query">
    		<tr>
    			<?php makeColumns() ?>
    		</tr>
    	</table>

    </body>
</html>