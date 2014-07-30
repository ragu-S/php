<?php

require("library.php");
$newDoc = new html();

$data = new dbConnect();
//$data->openConnection();
$arrayTable = $data->retrieveRow("inventory");

$data->getColumns("inventory");
$newDoc->htmlHead();
$newDoc->htmlBody();
?>

<div class="searchItem">
    <form action="sampleView.php" method="post">
        Search in description: 
        <input type="text" name="searchText" />
        <input type="submit" value="Search" />
    </form>
</div>
<table class="content_query">
    <tr>
        <td>ID</td>
        <td>Item Name</td>
        <td>Description</td>
        <td>Supplier</td>
        <td>Cost</td>
        <td>Price</td>
        <td>Number On Hand</td>
        <td>Reorder Level</td>
        <td>Back Order</td>
        <td>Delete/Restore</td>
    </tr>
    <?php
    if(count($arrayTable) > 1) { 
        $first = true;
        foreach($arrayTable as $row) {
            ?>
            <tr>
            <?php
            foreach($row as $attr => $val) {
               if($attr == 'deleted') {
                    $val == 'y' ? $val = "Restore" : $val = "Delete";
                    ?>
                    <td>
                        <a href="delete.php?id=<?php print($row['id']);?>&deleted=<?php print($row['deleted']);?>"><?php print($val) ?></a>
                    </td>
                    <?php
                }
                else {
                    ?>
                    <td><?php print($val); ?></td>
                    <?php
                }
            } 
            ?>
            </tr>
            <?php 
        }
    }
    else {
        ?>
        <tr>
            <td colspan="10">No entries to display</td>
        </tr>
        <?php
    }
    ?>
</table>

<?php

$newDoc->htmlFooter();

?>