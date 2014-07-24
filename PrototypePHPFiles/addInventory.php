<?php
/**
 * Created by PhpStorm.
 * User: PCUser
 * Date: 6/18/14
 * Time: 12:46 AM
 * for coding standards follow PEAR, see sample file on the site
 */

require("add2.php");
$html = new html();
$html->htmlHead();
?>
    <!-- <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Assignment</title>
        <script>
        </script>
    </head>
    <body> -->

<?php

$form = new formUserRegisteration();

//$firstName = new textField("firstName2");
//assertT("Object name is " . $firstName->displayItem());

// $regExp = ("/(\s{20}|[^a-z\-;\:\,\'0-9\s])/i"); //"/^([a-z\-;\:\,\'0-9 ]{0}|[ ]{20}|[^a-z\-;\:\,\'0-9 ])$/i"
// $invalid = array();
// $result = preg_match($regExp, " ", $invalid);

// if(is_array($invalid) && count($invalid)) 
//     $invalid = $invalid[0];
// else
//    $invalid = 0; 
// INSERT INTO `user`(title, firstName, lastName, organization, emailAddress, phoneNumber, monday, tuesday, tShirt)
// values("mr", "Rohit", "Verma", "waterloo", "rhoit@yahoo.ca", 6478979807, 1, 1, "l");
// assertT("Result: " . $result ." & " . $invalid);

if($_POST || $_GET) {
    if($form->validateForm()) { 
        $data = new dbConnect();
        //$data->insertItems($_POST);
    }
}
    //print_r($_POST);
//     if($form->validateForm()) { // if form is valid add form values to database
//         $data = new dbConnect();
//         //$data->returnArry();
//         //$data->openConnection();
//         //array_pop($_POST);
        
//         // if(isset())
//         // array_push($_POST, 'tuesday'=>false);
//         //$data->retrieveRow();
//         $data->insertItems($_POST);
//     }
//     else { // form had invalid entries
//         //alterButtonRow();
//     }
// }
// elseif($_GET) {
//     if($form->validateForm()) { // if form is valid add form values to database
//         ;
//     }
// }

//$form->storePostValues();
?>

<form action="add.php" method="post">
    <table name="userRegistration" class="registration">
        <tr>
            <td>Item name:</td>
            <td><input name="firstName" type="text" value="<?= $form->displayItem("firstName"); ?>"></td>
            <?= $form->showFormErrors("firstName");?>
        </tr>
        <tr>
            <td>Description:</td>
            <td><input name="lastName" type="text" value="<?= $form->displayItem("lastName"); ?>"></td>
            <?= $form->showFormErrors("lastName");?>
        </tr>
        <tr>
            <td>Organization:</td>
            <td><input name="organization" type="text" value="<?= $form->displayItem("organization"); ?>"></td>
            <?= $form->showFormErrors("organization");?>
        </tr>
        <tr>
            <td>Email address:</td>
            <td><input name="email" type="text" value="<?= $form->displayItem("email"); ?>"></td>
            <?= $form->showFormErrors("email");?>
        </tr>
        <tr>
            <td>Phone number:</td>
            <td><input name="phone" type="text" value="<?= $form->displayItem("phone"); ?>"></td>
            <?= $form->showFormErrors("phone");?>
        </tr>
        <tr>
            <td>Days attending:</td>
            <td>
                <input name="monday" type="checkbox" value="1" <?= $form->displayItem("monday");?>>Monday
                <input name="tuesday" type="checkbox" value="1" <?= $form->displayItem("tuesday"); ?>>Tuesday
            </td>
            <?= $form->showFormErrors("monday");?>
        </tr>
        <tr>
            <td>T-shirt size:</td>
            <td>
                <select name="tShirt">
                    <option>--Please choose--</option>
                    <option value="s" <?= $form->displayItem("tShirt", "s"); ?>>Small</option>
                    <option value="m" <?= $form->displayItem("tShirt", "m"); ?>>Medium</option>
                    <option value="l" <?= $form->displayItem("tShirt", "l"); ?>>Large</option>
                    <option value="xl" <?= $form->displayItem("tShirt", "xl"); ?>>X-Large</option>
                </select>
            </td>
            <?= $form->showFormErrors("tShirt");?>
        </tr>
        <tr><td><br></td></tr>
        <tr>
            <td></td>
            <td><input name="submit" type="submit"></td>
        </tr>
    </table>
</form>



<?php
$html->htmlFooter();
?>
