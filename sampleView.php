<?php

require("library.php");
$newDoc = new html();
$data = new dbConnect();
$search = new searchItem();

$arrayTable = array();
$message;
$sort = null;

function setSearchCookie($item = null) {
    $month = time() + (3600 * 24 * 30);
    if($item != null) {
        $success = setcookie("search", $item, $month);
    }
}

function getSearchCookie() {
    if(isset($_COOKIE['search'])) {
        return $_COOKIE['search'];
    }
    else {
        return null;
    }
}

if(isset($_GET['sort'])) {
    print_r($data->getColumns("inventory"));
    //$sort = getSearchCookie();
    $sort = $_GET['sort'];
}
//$data->insert("userlogin",array("fg"));

if(isset($_POST['searchText'])) {
    var_dump($_POST);
    //if() {
        if($search->validate()) { 
            $arrayTable = $search->search($data, $sort);   
        }
        elseif(preg_match("/^\s*$/i",$_POST['searchText'])) {
            // blank search, show all  
            $arrayTable = $data->retrieveAll("inventory" , $sort);
            // sort
            //$search->sortTable($sort,$data);      
        }
        //}
}
else {
    print("No form submit");
    $arrayTable = $data->retrieveAll("inventory", $sort);
}

// Sorting functionality
// store search term
// store view term

//print_r($_POST); 
//$data->openConnection();

$data->getColumns("inventory");
$newDoc->htmlHead();
$newDoc->htmlBody();
?>

<div class="searchItem">
    <form action="sampleView.php" method="post">
        Search in description: 
        <input type="text" name="searchText" value="<?php if(isset($_POST['searchText'])) toHtml($_POST['searchText']);?>" />
        <input type="submit" value="Search" />
    </form>
</div>
<table class="content_query">
    <tr>
        <td><a href="sampleView.php?sort=0;?>">ID</a></td>
        <td><a href="sampleView.php?sort=1;?>">Item Name</a></td>
        <td><a href="sampleView.php?sort=2;?>">Description</a></td>
        <td><a href="sampleView.php?sort=3;?>">Supplier</a></td>
        <td><a href="sampleView.php?sort=4;?>">Cost</a></td>
        <td><a href="sampleView.php?sort=5;?>">Price</a></td>
        <td><a href="sampleView.php?sort=6;?>">Number On Hand</a></td>
        <td><a href="sampleView.php?sort=7;?>">Reorder Level</a></td>
        <td><a href="sampleView.php?sort=8;?>">Back Order</a></td>
        <td><a href="sampleView.php?sort=9;?>">Delete/Restore</a></td>
    </tr>
    <?php
    if(count($arrayTable) > 1) { 
        $first = true;
        foreach($arrayTable as $row) {
            ?>
            <tr>
            <?php
            foreach($row as $attr => $val) {
                if($attr == 'id') {
                    ?>
                    <td>
                        <a href="addItem.php?id=<?php toHtml($row['id']);?>&deleted=<?php print($row['deleted']);?>"><?php print($val) ?></a>
                    </td>
                    <?php
                }
                elseif($attr == 'deleted') {
                    $val == 'y' ? $val = "Restore" : $val = "Delete";
                    ?>
                    <td>
                        <a href="delete.php?id=<?php toHtml($row['id']);?>&deleted=<?php print($row['deleted']);?>"><?php print($val) ?></a>
                    </td>
                    <?php
                }
                else {
                    ?>
                    <td><?php toHtml($val); ?></td>
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
            <td colspan="10"><?php toHtml((isset($messages)) ? $messages :"No entries to display"); ?></td>
        </tr>
        <?php
    }
    ?>
</table>

<?php
displayError();
$newDoc->htmlFooter();

?>