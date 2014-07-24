<?php
// library.php
// Contains all class declerations

class Html {
    public function htmlHead() {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Ragu's Online Magical Trinkets</title>
            <script>
            </script>
            <link href="./styles/style.css" rel="stylesheet">
        </head>
        <body>
        <?php
    }

    public function htmlBody() {
        ?>
        
        <?php
    }

    public function htmlFooter() {
        ?>
            <footer>
                <p>footer</p>
            </footer>
        </body>
        </html>
        <?php
    }
}

function assertT($parm) {
    if(strlen($parm)) {
        $output = '<script>console.log("#");</script>';
        $output = str_replace("#", $parm, $output);
        print $output;
    }
}

class ItemEntry {
    protected $_name;
    protected $_regEx;
    protected $_error;
    protected $_valid;
    protected $_value;

    public function __construct($name) {
        if(is_string($name)) {
            $this->_name = $name;
        }
        // default textfield regEx
        $this->_regEx = "/(^\s*$)|([^a-z\-;\:\,\'0-9\s])/i";
        $this->_error = "Invalid Entry: " . $this->_name . " must be set";        

        $this->_valid = false;
    }
    public function getItem($itemName) {
        return $this->$itemName;
    }
    public function setRegEx($regEx) {
        $this->_regEx = $regEx;
    }
    public function setFormValue($value) {
        $this->_value = $value;
    }
    public function getError() {
        //assertT("Calling getError() ".$this->_error);
        return $this->_error;
    }
    public function isValid() {
        return $this->_valid;
    }
    public function validate($formValue) { // base validation
        //$valid = true;
        $invalidChars = array();
        $this->_error ="Invalid Entry: " . $this->_name;
        // Check to see if POST or GET had been set for this form object
        if(isset($_POST[$this->_name]) || isset($_GET[$this->_name])) {
            // Check to see if regex pattern has been set for object validation
            // Check if form field had been blank (including just whitespace characters)
            if($this->_regEx !== null && !(preg_match("/^(\s| )*$/", $formValue))) {
                // Check for any invalid characters
                if(preg_match($this->_regEx, $formValue, $invalidChars)) {
                    // if true make an error message to be printed later with the invalid character
                    if(count($invalidChars)) {
                        $this->_error .= " cannot have $invalidChars[0]";
                    } 
                    // a catch all to see what didnt get validated
                    else {
                        $this->_error .= " cannot be empty";
                    }
                }
                // Form entry is VALID
                else {
                    assertT("preg false " . $this->_name);
                    $this->_error = false;
                    $this->_valid = true;
                }
            }
            // a blank form, or with only whitespace characters
            else {
                $this->_error = "$this->_name cannot be blank";
            }
        }
        return $this->_valid;
    }

    // Used for form-repopulation
    public function displayItem() {
        if($this->isValid()) {
            return $_POST[$this->_name];
        }
    }

    // Used for showing errors in form entry
    public function showFormErrors() {
        if(($_POST || $_GET) && !$this->isValid()) {
            ?>
                <td class="error"> <?php echo $this->getError();?></td>
            <?php
        }
    }
}

// class RadioButton extends ItemEntry
class RadioButton extends ItemEntry {
    // If button checked, then display checked flag for HTML 
    public function displayItem($formName, $formValue) {
        if($this->isValid()) {
            if(isset($_POST[$this->_name])) { //&& (!strcmp($_POST[$this->_name], $formValue))) { 
                if(strcmp($_POST[$this->_name], $formValue) == 0) { 
                    return "checked"; 
                }
            }
            elseif(isset($_GET[$this->_name])) { //&& (!strcmp($_GET[$this->_name], $formValue))) {
                if(strcmp($_GET[$this->_name], $formValue) == 0) { 
                    return "checked";
                }
            }
        }
    }
}

// class TextField extends ItemEntry
class TextField extends ItemEntry {
    public function validate($value = null) {
        assertT("calling TextField fgValidate");
        if($value !== "--Please choose--") { // check if default is set
            $this->_valid = true;
            $this->_error = false;
        }
        else {
            $this->_valid = false;
            $this->_error .= " size must be checked";
        }
        return $this->_valid;
    }
}

class Password extends ItemEntry {
    public function validate($value = null) {
        if($value !== null) {
            // do html encoding validation
            $this->_password = htmlencoding($value);
        }
        else {
            $this->_valid = false;
            $this->_error .= " requires password";
        }
        return $this->_valid;
    }
}

// class selectOption extends ItemEntry
class SelectField extends ItemEntry {
    private $selected;
    public function validate($value = null) { 
        assertT("calling SelectField fgValidate");
        if(isset($_POST[$this->_name]) || isset($_GET[$this->_name])) {
            if($value !== "--Please choose--") { // check if default is set
                $this->_valid = true;
                $this->_error = false;
            }
            else {
                $this->_valid = false;
                $this->_error = "$this->_name size must be selected";
            }
        }
        return $this->_valid;
    }
    public function displayItem($formName, $formValue) {
        if($this->isValid() && $formValue == $_POST[$this->_name]) {
            return "selected";
        }
    }
}

// class CheckBox extends ItemEntry
class CheckBox extends ItemEntry {
    public function __construct($name, $regEx = null) {
        ItemEntry::__construct($name, $regEx);
        $this->_error = "Date must be checked";
    }

    public function validate($value = null) {
        if($value == "1") {
            $this->_valid = true;
            $this->_error = false;
        }
        else {
            isset($_POST[$this->_name]) ? $_POST[$this->_name] = 1 : $_POST[$this->_name] = 0;
            isset($_GET[$this->_name]) ? $_GET[$this->_name] = 1 : $_GET[$this->_name] = 0;
            //$this->_valid = false;
            $this->_error = false;
        }
    }

    public function showFormErrors() {
        if(($_POST || $_GET) && !$this->isValid()) {
            //return "<td class=\"form_error\">".$this->getError()."</td>";
            ?>
                <td class="error"> <?php echo $this->getError();?></td>
            <?php
        }
    }

    public function displayItem() {
        if($this->isValid()) {
            return "checked";
        }
    }
}

// class textArea extends ItemEntry

class FormItemEntry {
    // attributes of form Item Entry class

    // Text Input fields
    private $itemName; 
    private $description; 
    private $supplierCode; 
    private $cost; 
    private $sellingPrice; 
    private $onHand;
    private $reorder;
    private $backOrder;

    // a flag to tell us if form had been validated
    private $formValid;

    public function __construct() {
        //Text Fields
        $this->itemName = new ItemEntry("itemName");
        $this->description = new ItemEntry("description");
        $this->supplierCode = new ItemEntry("supplierCode");
        $this->cost = new ItemEntry("cost");
        $this->sellingPrice = new ItemEntry("sellingPrice");
        $this->onHand = new ItemEntry("onHand");
        $this->reorder = new ItemEntry("reorder");

        //Checkboxes
        // first register a CheckBox object for all checkbox types
        // $this->CheckBox = new CheckBox("value", "value", "value");
        // in validation check to see if CheckBox object is instantiated
        $this->backOrder = new CheckBox("backOrder");
    }
    public function makeFormElement($name, $regEx = null) {
        $formItem = new ItemEntry($name);
        $formItem->setRegEx($regEx); 
        array_push($this->formElement, $formItem);
    }
    public function getFormElement($name = null) {
        // foreach($this->formElement as $element) {
        //     assertT("form Element is $element");
        // }
        var_dump($this->formElement);
    }
    public function hasRegEx($item) {
        return $this->$item->getItem("_regEx") !== NULL;
    }
    public function addOption($item) {
        foreach($item as $entry)
            echo makeTag($entry, "option");
    }
    public function displayItem($formName, $value = null) {
        if($value === null)
            echo $this->$formName->displayItem($formName);
        else
            echo $this->$formName->displayItem($formName, $value);
    }
    public function validateForm() { // make sure u have for GET when implementing headers
        $CheckBoxEnabled = false;
        //$index = count($_POST);// || count($_GET);
        $this->formValid = false;
        if($_SERVER["REQUEST_METHOD"] == "POST") { // or better $_SERVER["REQUEST_METHOD"] == "POST"
            unset($_POST['submit']);
            foreach($_POST as $key=>$value) {     
                $this->formValid = $this->$key->validate($value);     
            }
        }
        elseif($_GET) {
            unset($_POST['submit']);
            foreach($_GET as $key=>$value) {      
                $this->formValid = $this->$key->validate($value);     
            }
        }
        //echo $this->formValid;
        return $this->formValid;
    }
    public function showFormErrors($formName) {
        echo $this->$formName->showFormErrors();
    }
    public function storeValues() {
        // Primary purpose for this class method is to input values into the userFormClassMain class
        if($this->formValid) {
            $this->formElement = array();
            if($_POST) {
                foreach($_POST as $key=>$value) {     
                    $formElement[$key] = trim($value);
                }
            }
            elseif($_GET) {
                foreach($_GET as $key=>$value) {     
                    $formElement[$key] = trim($value);
                }
            }
        }
    }
    function __destruct() {
    }
}

class DbConnect {
    private $pdo;
    private $DB_NAME;

    public function __construct() {
        $error = false;
        $authenticate = null;
        //if(flock($fp, LOCK_EX) && 
        if($authenticate = file("./topsecret.txt")) {
            $error = false;
            //preg_match("/(?<=: ).*$/i", $connectionInfo);
        }
        else {
            echo ("unable to open topsecret file for connection");
            //error_log("unable to open topsecret file for connection", 0);
            $authenticate = null;
        }
        //print(strlen($authenticate));
        $DB_HOST = $authenticate[0];
        $this->DB_NAME = $authenticate[3];
        $DB_USERNAME = $authenticate[1];
        $DB_PASSWORD = $authenticate[2];
        $DB_PORT = "3306";

                      // PDO(localhost, database, username, password)
        $this->pdo = new PDO("mysql:host=localhost;dbname=gamesite", "root");
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->exec('set session sql_mode = traditional');
        $this->pdo->exec('set session innodb_strict_mode = on');
    }

    public function retrieveRow($tablename = "user") {
        try {
            $arryTable = array();
            $stmt = $this->pdo->prepare("select * from $tablename");
            $stmt->execute();
            while($row = $stmt->fetch()) {
                array_push($arryTable, $row);
            } 
            return $arryTable;// = $stmt->fetch();
        }
        catch(PDOException $e) {
            die(htmlspecialchars($e->getMessage()));
        }
    }

    public function insertItems($inputArry, $tablename = "user") {
        $insert = $this->pdo->prepare("INSERT INTO $this->DB_NAME.$tablename".
        "(title, firstName, lastName, organization, email, phone, monday, tuesday, tShirt)".
        "VALUES (:title, :firstName, :lastName, :organization, :email, :phone, :monday, :tuesday, :tShirt)"); 
        if(count($inputArry)) {
            // trim input before submitting to database
            foreach($inputArry as $key=>$value) {
                trim($inputArry[$key]);
            }
            //print_r($inputArry);  
            $insert->execute($inputArry);
        }
        else {
            echo "error in insertion command";
        }
    }
    public function selectAllItems($itemId, $tablename = "user") {
        $select = $this->pdo->prepare("SELECT * FROM $this->DB_NAME.$tableName");
        $select->executre();
    }
    public function deleteItem($itemId, $tablename = "user") {
        $alterFlag = $this->pdo->prepare("UPDATE $this->DB_NAME.$tablename ".
                "SET phoneNumber='6478933422' ".
                "WHERE userId = $itemId");
        $alterFlag->execute();
    }
    public function __destruct() {
        $this->pdo = null;
    }
}

?>