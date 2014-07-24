<?php

require("add2.php");
$newDoc = new html();

$data = new dbConnect();
//$data->openConnection();
$arrayTable = $data->retrieveRow();
//print_r($arrayTable);
$newDoc->htmlHead();
?>

<!-- <section class="wrapper">
    <div class="row purple">
        <div class="column-3 red">1</div>
        <div class="column-6">2</div>
        <div class="column-3 red">3</div>
        <div class="column-3 red">4</div>
    </div>
</section> -->

<table class="content_query">
    <tr>
        <td>id</td>
        <td>Title</td>
        <td>First Name</td>
        <td>Last Name</td>
        <td>Organization</td>
        <td>Email</td>
        <td>Phone</td>
        <td>Monday</td>
        <td>Tuesday</td>
        <td>T-Shirt Size</td>
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
               // print_r($row);
            ?>
                <td><?php print($val); ?></td>
            <?php
            } 
            ?>
            <td>
                <a href="delete.php?id=<?php print($row['userId']); ?>"  />Delete
            </td>
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