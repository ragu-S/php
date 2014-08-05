<?php

require("library.php");

$session = new Session();

if(!$session->sessionActive()) {
    // User was not logged or was timed out, redirect to login
    addError("Session is not Active");
    
    redirect("login.php");
}
addError("CURRENT COOKIE ".$_COOKIE['search']);

$arrayTable = array();
$message;
$sort = getPreviousSearch();

$data = new dbConnect();
$search = new searchItem();

$html = new html();
$menu = new Menu();
$html->htmlHead();
$menu->displayMenu("view");

if(isset($_GET['sort'])) {
    $tableColumns = $data->getColumns("inventory");
    if(isset($tableColumns[$_GET['sort']])) {
        $sort = $tableColumns[$_GET['sort']];
    }
    setSearchCookie($sort);
}

//$data->insert("userlogin",array("fg"));
print("SORT is ".$sort);
if(isset($_POST['searchText'])) {
    //var_dump($_POST);
    if($search->validate($_POST['searchText'])) { 
        addError("VALID search key");
        $_SESSION['searchTerm'] = $search->getSearchTerm();
        $arrayTable = $search->search($data, $sort); 
    }
    elseif(preg_match("/^\s*$/i",$_POST['searchText'])) {
        addError("Empty search key");
        // blank search, show all  
        $arrayTable = $data->retrieveAll("inventory" , array('sort' => $sort));
        // sort
        //$search->sortTable($sort,$data);      
    }
}
elseif(isset($_SESSION['searchTerm']) && isset($_GET['sort'])) {
    addError("Previous SEARCH from SESSION variable");
    if($search->validate($_SESSION['searchTerm'])) {
        $arrayTable = $search->search($data, $sort);    
    }
}
else {
    print("No form submit");
    if(isset($_SESSION['searchTerm'])) {
        unset($_SESSION['searchTerm']);
    }
    $arrayTable = $data->retrieveAll("inventory", array('sort' => $sort));
}

// Sorting functionality
// store search term
// store view term

//print_r($_POST); 
//$data->openConnection();

$data->getColumns("inventory");

?>
<table class="content_query">
    <tr>
        <td><a href="sampleView.php?sort=0">ID</a></td>
        <td><a href="sampleView.php?sort=1">Item Name</a></td>
        <td><a href="sampleView.php?sort=2">Description</a></td>
        <td><a href="sampleView.php?sort=3">Supplier</a></td>
        <td><a href="sampleView.php?sort=4">Cost</a></td>
        <td><a href="sampleView.php?sort=5">Price</a></td>
        <td><a href="sampleView.php?sort=6">Number On Hand</a></td>
        <td><a href="sampleView.php?sort=7">Reorder Level</a></td>
        <td><a href="sampleView.php?sort=8">Back Order</a></td>
        <td><a href="sampleView.php?sort=9">Delete/Restore</a></td>
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
?>