<?php
/**
 * This file contains all the necessary classes and functions for the web site
 * to:
 *     Form Classes:
 *       1. Create form variables to store validated or sanitized user input
 *       2. Validate form variables with regex
 *       3. Methods that repopulate forms and show errors
 *
 *     Database:
 *       1. Setup secure database connection
 *       2. Define CRUD functionality through Class methods 
 *       3. Execute CRUD commands
 *       
 *     HTML classes:
 *         1. Setup common header pages to be used by all php files that display HTML
 *         2. 
 *
 *  PHP version 5
 *
 *  @category PHP site
 *  @author Ragu. S <ragu.sivanandha@gmail.com>
 *  @link http://<need a web hoster still!>
 */


// }}}
// {{{ GLOBALS

/**
 * Global error message array to keep track of development runtime errors
 * @global array $GLOBALS['ERROR_MSGS']
 */
$GLOBALS['ERROR_MSGS'] = array();

// }}}
// {{{ addError()

/**
 * Adds error messages to $GLOBALS['ERROR_MSGS'] along with error number, location of error call
 * and error description
 *
 * @param string $err the error message to display later
 * @param string $class the class name if error took place in class
 * @param string $method the method name if error took place in method
 *
 */

function addError($err, $class = null, $method = null) {
    $errNum = count($GLOBALS['ERROR_MSGS']) + 1;   
    $errMsg = "Error $errNum: ";
    $errMsg .= ($class ? ($method ? "$class->$method $err" : $err) : $err);
    array_push($GLOBALS['ERROR_MSGS'], $errMsg);
    // assertNotEmpty(actual, 'message');
}

//}}}
//{{{

/**
 * Displays error by accessing the global array key and outputting each error to console log
 * 
 */
error_reporting(E_ALL);

function displayError() {
    if(count($GLOBALS['ERROR_MSGS'])) {
        foreach ($GLOBALS['ERROR_MSGS'] as $errMsg) {
            if(is_string($errMsg)) {
                assertT($errMsg);
            }
        }
    }
}

// Used for removing dangerous special chars and unecessary spaces form input
function removeSpecialChars($input) {
    $input = trim($input);
    $input = stripslashes($input);
    //$input = mysqli_real_escape_string($input);
    //$input = htmlspecialchars($input);
    return $input;
}

/**
 * 
 */

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

/**
 * 
 */

class Menu {
    private $_menuItems;
    //$menuArray = array();
    public function __construct() {
        $this->_menuItems = array(['link' => 'Add',      'path' => 'addItem.php'],
                                  ['link' => 'View All', 'path' => 'sampleView.php']
                                             
        );
        // ['link' => '',   'path' => '.php']
        // ['link' => 'login',   'path' => 'login.php']
        // ['link' => 'Logout',   'path' => 'logout.php']
        // 
    }   
    public function addView() {
        ?>
        <nav>
            <ul>
            <?php    
                foreach($this->_menuItems as $item) {
                    ?>
                    <li><a href="<?php toHtml($item['path']); ?>"><?php toHtml($item['link']);?></a></li>    
                    <?php
                }
        ?>
        </ul>
        </nav>
        <?php
    }
    public function searchBox() {
        ?>
            <div class="searchItem">
                <form action="sampleView.php" method="post">
                    Search in description: 
                    <input type="text" name="searchText" value="<?php if(isset($_POST['searchText'])) toHtml($_POST['searchText']); elseif(isset($_SESSION['searchTerm']) && isset($_GET['sort'])) toHtml($_SESSION['searchTerm']); ?>" />
                    <input type="submit" value="Search" />
                </form>
            </div>
        <?php
    }
    public function userLogInfo() {
        if(isset($_SESSION['valid_user']) && isset($_SESSION['role'])) {
            ?>
                <ul class="currentUser">
                    <li><?php toHtml($_SESSION['valid_user']); ?></li>
                    <li><?php toHtml($_SESSION['role']); ?></li>
                    <li><a href="logout.php">Log Out</a></li>    
                </ul>
            <?php
        }
    }
    public function displayMenu($view = false) {
        print_r($_SESSION);
        ?>
        <div class="menu">
            <?php
            $this->addView();
            if($view) {
                $this->searchBox();
            } 
            $this->userLogInfo();
            ?>
        </div>
        <?php
    }
}

/**
 * 
 */

function assertT($parm) {
    if(strlen($parm)) {
        $output = '<script>console.log("#");</script>';
        $output = str_replace("#", $parm, $output);
        print $output;
    }
}

/**
 * 
 */

class ItemEntry {
    protected $_name;
    protected $_regEx;
    protected $_error;
    protected $_valid;
    protected $_validateField;
    protected $_whitelist;
    protected $_modify;

    public function __construct($name, $validate, $whitelistValidation = false) {
        if(is_string($name)) {
            $this->_name = $name;
        }

        //addError("constructor called", $this->_name, "contruct");
        // default textfield regEx
        
        $this->_whitelist = $whitelistValidation;
        $this->_regEx = "/(^\s*$)|([^a-z\-;\:\,\'0-9\s])/i";
        $this->_error = "Invalid Entry: this field ";
        $this->_validateField = $validate;        
        $this->_valid = false;
        $this->_modify = false;
    }

    public function getItem($itemName) {
        return $this->$itemName;
    }

    public function setRegEx($regEx) {
        $this->_regEx = $regEx;
    }

    public function getError() {
        return $this->_error;
    }
    public function makeModifiable() {
        print($this->_name);
        $this->_modify = true;
        $this->_valid = true;
        $this->_error = null;
    }
    /**
     * Default flag for this form field is set to false, i.e. invalid, set true only after validation checks are passed
     * @param  string $formValue form input from user
     * @return bool if form valid return true
     */
    
    public function validate() { 
        // Used to store invalid characters user entered from form field
        $invalidChars = array();
        // Check if we need to validate this field first
        // Check to see if POST had been set for this form object
        if($this->_validateField && isset($_POST[$this->_name])) {
            // Check if form field had been blank (including just whitespace characters)    
            $notBlanks = ($this->_regEx !== null) && (preg_match("/^\s*$/", $_POST[$this->_name]));
            
            // Special check to see if form field is blank, show as required in view
            if($notBlanks) {
                $this->_error = "This field is required";
            }
            // If checking whitelist validation (check for valid characters only, rather than invalid), preg_match should return true if valid
            elseif($this->_whitelist && preg_match($this->_regEx, $_POST[$this->_name])) {
                 addError("preg_match passed, whitelist validation $this->_name");
                // Form entry is VALID
                $this->_error = false;
                $this->_valid = true;
            }
            // Check for any invalid characters 
            // Note: reason we check for whitelist flag is to ensure that if above check failed, and we try match with whitelist regex it
            // would return only the valid characters in the form input
            elseif(!$this->_whitelist && preg_match($this->_regEx, $_POST[$this->_name], $invalidChars)) {
                addError("preg_match passed, none whitelist $this->_name");
                // if true make an error message to be printed later with the invalid character
                if(count($invalidChars)) {
                    $this->_error .= " cannot have $invalidChars[0]";
                } 
            }
            else {
                // Form entry is VALID
                $this->_error = false;
                $this->_valid = true;
            }
        }
        // if we dont need to validate simply set valid field to true
        elseif(!$this->_validateField) {
            $this->_valid = true;
        }
        return $this->_valid;
    }

    // Used for form-repopulation
    public function displayItem() {
        if((isset($_POST[$this->_name]) && !$this->_valid) || $this->_modify) {
            return $_POST[$this->_name];
        }
    }

    // Used for showing errors in form entry
    public function showFormErrors() {
        if($_POST && !$this->_valid) {
            ?>
                <td class="error"> <?php toHtml($this->getError()); ?></td>
            <?php
        }
    }
}

/**
 * Login class that handles validation of username and password, including encryption
 */

class Login {
    private $_username;
    private $_password;
    private $_role;
    private $_passwordHint;
    private $_regExUser;
    private $_regExPassword;
    private $_error;
    private $_formValid;
    
    public function __construct() {        
        //and _password statisfy the requirements
        //$this->_formName = $formName;
        $this->_username = new ItemEntry("username", true, true);
        $this->_password = new ItemEntry("password", true, false);
        $this->_role = new SelectField("role", false);
        $this->_passwordHint = new ItemEntry("passwordHint", true, true);

        $this->_username->setRegEx("/(^[0-9a-z]+@[a-z]+\.[a-z]+$)/i");
        $this->_password->setRegEx("/(^\s*$)|(^[0-9a-z!@#$%^&*()]{1,7}$)|([^0-9a-z!@#$%^&*()])/i");
        $this->_passwordHint->setRegEx("/^[a-z0-9]+$/i");

        $this->_formValid = false;
    }

    public function loginValidate() {
        // Set form valid to 0, then count number of errors, form invalid if error count > 0
        $this->_formValid = false;

        if(isset($_POST['username']) && isset($_POST['password'])) {
            print_r($_POST);
            if($this->_username->validate() && $this->_password->validate()) {
                $this->_formValid = true;
            }

            if($this->_formValid) {
                // Remove special characters if they exist (as a precaution)
                $_POST['username'] = removeSpecialChars($_POST['username']);
                $_POST['password'] = removeSpecialChars($_POST['password']);

                // encrypt password with salt $1$1p0rHF1b$    
                $_POST['password'] = crypt($_POST['password'], "$1$1p0rHF1b$");   
            }
            else {
                $this->_error = "Invalid username or password";
                $this->_formValid = false;
            }
        }
        else {
            $this->_error = "Username or password cannot be empty";
            $this->_formValid = false;
        }
        return $this->_formValid;
    }

    public function showFormErrors() {
        if($_POST && !$this->_formValid) {

            ?>
            <tr>
                <td class="error" colspan="2">
                    <?php toHtml($this->_error); ?> 
                </td>
            </tr>
            <?php
        }
    }

    public function isUserRegistered($database) {
        $query = "SELECT username FROM gamesite.userlogin
                  WHERE username = ?";
                  print("isUserRegistered");
        //$userInfo = array(':username' => "%%"); 

        $queryResult = $database->retrieveRow("userlogin", array($_POST['username']), $query);
        
        if(isset($queryResult['username'])) {
            addError("Username and password are already taken");
            $this->_formValid = false;
        }
        else {
            $this->_formValid = true;
        }
        return !$this->_formValid;
    }

    public function registerUser($database) {
        $registered = false;
        //var_dump($_POST);
        addError("Validating");
        // if Role and password hint had been given, sanitize the input and store as values
        if(isset($_POST['passwordHint'])) {
            if(!$this->_passwordHint->validate()) {
                $this->_error = "Password hint is required";
                $this->_formValid = false;
            }
            else {
                //$this->_role = $_POST['role'];
                $_POST['passwordHint'] = removeSpecialChars($_POST['passwordHint']);
                $this->_formValid = true;
            }
            addError("USER REGISTRATION valid");
        }
        //'boyczuk@senecacollege.ca',  hallgkdls,'$1$1p0rHF1b$iDaVRxMxTTk8qIF8baTw21'       
        if($this->_formValid) {
            if(!$this->isUserRegistered($database)) {
                addError("Inserting to user table");
                // $query = "INSERT INTO gamesite.userlogin".
                //          "(username, password, role, passwordHint)".
                //          " VALUES(':username', ':password', ':role', ':passwordHint')";
                $userInfo = array('username'     => $_POST['username'],
                                  'password'     => $_POST['password'],
                                  'role'         => $_POST['role'],
                                  'passwordHint' => $_POST['passwordHint']
                                 );
                //print_r($_POST);
                $registered = $database->insert("userlogin", $userInfo);
            }
            else {
                $registered = false;
                $this->_error = "This username or password is invalid";
                $this->_formValid = false;
            }
        }

        return $registered;
    }
}

// class RadioButton extends ItemEntry
/**
 * 
 */

class RadioButton extends ItemEntry {
    // If button checked, then display checked flag for HTML 
    public function displayItem($formValue) {
        if(isset($_POST[$this->_name])) {
            if(strcmp($_POST[$this->_name], $formValue) == 0) { 
                return "checked"; 
            }
        }
    }
}

// class 
/**
 * SelectField extends ItemEntry, adding methods that override the ItemEntry methods for this class
 * SelectField needs to check which option was selected, then return a selected key for the HTML tag
 */


// class selectOption extends ItemEntry
class SelectField extends ItemEntry {
    private $selected;

    public function validate($value = null) { 
        if(isset($_POST[$this->_name])) {
            if($value !== "--Please choose--") { // check if default is set
                $this->_valid = true;
                $this->_error = false;
            }
            else {
                $this->_valid = false;
                $this->_error = "This field must be selected";
            }
        }
        return $this->_valid;
    }

    public function displayItem($formValue) {
        if($this->_valid && $formValue == $_POST[$this->_name]) {
            return "selected";
        }
    }
}

// class CheckBox extends ItemEntry
class CheckBox extends ItemEntry {
    public function __construct($name, $validate){
        ItemEntry::__construct($name, $validate, false);
        if(is_string($name)) {
            $this->_name = $name;
        }
        $this->_validateField = $validate;        
        $this->_error = "This field must be checked";
        $this->_valid = true;
    }

    public function validate() {
        if(isset($_POST[$this->_name])) {
            $_POST[$this->_name] = "y";   
        }
        $this->_valid = true;
        addError("Checkbox Validation END ".$this->_valid);
        return $this->_valid;
    }

    public function showFormErrors() {
        if(isset($_POST[$this->_name]) && !$this->_valid) {
            ?>
                <td class="error"> <?php toHtml($this->_error); ?></td>
            <?php
        }
    }

    public function displayItem() {
        if((isset($_POST[$this->_name]) && !$this->_valid) || $this->_modify) {
            return "checked";
        }
    }
}

// class textArea extends ItemEntry
/**
 * 
 */

class TextArea extends ItemEntry {

    // public function __construct($formName) {
    //     ItemEntry::__construct($formName);
    //     //$this->_regEx = "/(^\s*$)|([^a-z0-9\.\,\'\"\- \s])/i";
    // }
    // public function displayItem() {
    //     if($this->_valid) {
    //         return "checked";
    //     }
    // }
}

class SearchItem {
    private $_searchTerm;
    private $_regEx;
    private $_valid;

    public function __construct() {
        //ItemEntry::__construct("searchText", true);
        $this->_searchTerm = "an item"; 
        $this->_regEx = "/([^a-z0-9\.\,\'\"\- \s])/i";
        $this->_valid = false;
    }

    public function validate($searchTerm) {
        /**
         * This function validates the Search term for invalid characters based on the Textarea field validation
         */  
        print($searchTerm);
        if(!preg_match($this->_regEx, $searchTerm)) {
            // Sanitize input value for illegal characters
            $searchTerm = stripslashes($searchTerm);
            $this->_searchTerm = $searchTerm;
            addError($searchTerm);
            $this->_valid = true;
        }
        else {
            $this->_error = "Search entry invalid";
            addError($this->_error);
        }
        
        return $this->_valid;
    }

    public function search($database, $sortItem = "id") {
        $db = $database->getDbName();
        print($this->_searchTerm);
        print($sortItem);
        $query = "SELECT * FROM $db.inventory". 
                 " WHERE description LIKE :description".
                 " ORDER BY $sortItem ASC";
        
        //$item = '"%'.$this->_searchTerm.'%"';
        
        $matches = $database->retrieveAll("inventory", array(':description' => "%$this->_searchTerm%"), $query);
        //$matches = $database->retrieveAll("inventory", arr, $query);
        //print($matches);
        return $matches;
    }

    public function getSearchTerm() {
        return $this->_searchTerm; 
    }
}

class FormItemEntry {
    // attributes of form Item Entry class

    // Text Input fields
    private $itemId;
    private $itemName; 
    private $description; 
    private $supplierCode; 
    private $cost; 
    private $price; 
    private $onHand;
    private $reorder;
    private $backOrder;

    // a flag to tell us if form had been validated
    private $_formValid;

    public function __construct() {
        //Text Field
        $this->id = new ItemEntry("id", false, false);
        $this->itemName = new ItemEntry("itemName", true, false);
        
        // Text Area
        $this->description = new TextArea("description", true, false);

        $this->supplierCode = new ItemEntry("supplierCode", true, true);
        $this->supplierCode->setRegEx("/^[a-z0-9\-\s]+$/i");

        $this->cost = new ItemEntry("cost", true, true);
        $this->cost->setRegEx("/(^[0-9]+(\.[0-9]{2}|[0-9]*)$)/i");

        $this->price = new ItemEntry("price", true, true);
        $this->price->setRegEx("/(^[0-9]+(\.[0-9]{2}|[0-9]*)$)/i");

        $this->onHand = new ItemEntry("onHand", true, false);
        $this->onHand->setRegEx("/[^0-9]/");
        $this->reorderPoint = new ItemEntry("reorderPoint", true, false);
        $this->reorderPoint->setRegEx("/[^0-9]/");

        //Checkboxes
        $this->backOrder = new CheckBox("backOrder", false, false);

        $this->_formValid = false;
    }
    public function makeFormElement($name, $regEx = null) {
        $formItem = new ItemEntry($name);
        $formItem->setRegEx($regEx); 
        array_push($this->formElement, $formItem);
    }

    public function addOption($item) {
        foreach($item as $entry)
            toHtml(makeTag($entry, "option"));
    }

    public function displayItem($formName, $value = null) {
        if($value === null)
            toHtml($this->$formName->displayItem($formName));
        else
            toHtml($this->$formName->displayItem($formName, $value));
    }

    public function validateForm($modify = false) { // make sure u have for GET when implementing headers
        $this->_formValid = 0;
        
        if(isset($_POST['submit'])) {
                unset($_POST['submit']);
        }

        // Set backOrder checkbox to no if there is no POST submission of checkbox(ie. was unchecked) 
        if(!isset($_POST['backOrder'])) {
            $_POST['backOrder'] = "n";
        }
        if($modify) {
            if(isset($_POST['deleted'])) {
                unset($_POST['deleted']);
            }
            addError("VALIDATE form called"); 
            print_r($_POST); 
            //unset($_POST['backOrder']);  
            foreach($_POST as $key=>$value) {
                $this->$key->makeModifiable();     
            }
            $this->_formValid = 0;
        }
        elseif($_SERVER["REQUEST_METHOD"] == "POST") { // or better $_SERVER["REQUEST_METHOD"] == "POST"
            if(isset($_POST['submit'])) { 
                unset($_POST['submit']);
            }
            // if(isset($_POST['backOrder'])) { 
            //     unset($_POST['id']); 
            // }
            //print_r($_POST);
            foreach($_POST as $key=>$value) {     
                print($key);
                if(!$this->$key->validate()) {
                    $this->_formValid++;
                    // addError("FormName: ".$key." Value: ".$value);            
                }     
            }
            addError("is form valid before Checkboxes?: " . $this->_formValid);
            // Validate Checkboxes
            // if(!$this->backOrder->validate()) {
            //     $this->_formValid++;
            // }
            addError("is form valid AFTER ?: " . $this->_formValid);
            // Set deleted to no for initial entry
            $_POST['deleted'] = "n"; 
        }
        //var_dump($_POST);
        
        //print($this->_formValid);
        return ($this->_formValid > 0) ? false : true;
    }

    public function showFormErrors($formName) {
        toHtml($this->$formName->showFormErrors());
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
        else {
            addError("Unable to open topsecret file for connection", get_class($this), "retrieveAllRows");
            $authenticate = null;
        }
    }

    public function getDbName() {
        return $this->DB_NAME;
    }

    public function retrieveRow($tablename, $queryItems) {
        try {
            $query = "SELECT * FROM $this->DB_NAME.$tablename";
            print_r($queryItems);
            $stmt = $this->pdo->prepare($query);
            $result = $stmt->execute($queryItems);

            return $result ? $stmt->fetch() : $result;
        }
        catch(PDOException $e) {
            die(addError($e->getMessage(), get_class($this), "retrieveRow"));
        }
    }

    public function retrieve($tablename, $columns, $selectAll) {
        try {
            $query = "";
            $cols = "";
            $criteria = "";
            $result = false;
            
            // if WHERE clause is to be included, retrieve values from $columns
            // AND comparison is set by default
            // if false, simply SELECT * from table
            
            foreach($columns as $colName => $value) {
                $cols .= "$colName, ";
                $criteria .= "$colName = :$colName AND ";
            }
            $cols = substr($cols, 0, strrpos($cols,", "));
            $criteria = substr($criteria, 0, strrpos($criteria,"AND "));    
            
            if($selectAll) {
                $cols = "*";
            }
            print($criteria);
            $query = "SELECT $cols FROM $this->DB_NAME.$tablename WHERE $criteria";

            $stmt = $this->pdo->prepare($query);
            $result = $stmt->execute($columns);
            
            return $result ? $stmt->fetch() : false;
        }
        catch(PDOException $e) {
            throw new Exception("Retrieve method expects two array parameters");
            die();
        }
    }

    public function retrieveAll($tablename, $sortarray, $query = null){//, $sortItem = "id") {
        try {
            $sort = null;
            $result = false;

            if($query === null && isset($sortarray['sort'])) {
                $query = "SELECT * FROM $this->DB_NAME.$tablename".//where description LIKE ?";//$tablename
                         " ORDER BY ". $sortarray['sort'] ." DESC";
                $stmt = $this->pdo->prepare($query);
                $result = $stmt->execute();
            }
            else {
                $stmt = $this->pdo->prepare($query);
                $result = $stmt->execute($sortarray);
            }
            print_r($sortarray);
            print($query);

            return $result ? $stmt->fetchAll() : $result;
        }
        catch(PDOException $e) {
            die(addError($e->getMessage(), get_class($this), "retrieveAllRows"));
        }
    }

    public function retrieveSpecial($query, $queryArray = null) {
        try {
            $result = false;
            addError("retrieveSpecial");
            if(is_array($queryArray)) {
                $stmt = $this->pdo->prepare($query);
                $result =  $stmt->execute($queryArray);
            }
            
            // print_r($query);
            // print_r($stmt->fetch());
            return $result ? $stmt->fetch() : $result;
        }
        catch(PDOException $e) {
            die(addError($e->getMessage(), get_class($this), "retrieveAllRows"));
        }
    }

    public function getColumns($tablename) {
        try {
            $stmt = $this->pdo->prepare("DESCRIBE $this->DB_NAME.$tablename");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
        catch(PDOException $e) {
            die(addError($e->getMessage(), get_class($this), "getColumns"));
        }
    }

    public function insert($tablename, $values, $ignorePK = null) {
        try {
            $result = false;
            if(is_array($values)) {
                $columns = $this->getColumns($tablename);
                $keys = array_keys($values);
                $tableCols = "";
                $placeHolders = "";


                print_r($columns);
                // We wish to remove an autoincremented primary key
                if($ignorePK != null && (array_search($ignorePK, $columns)) !== false) {
                    array_shift($columns); 
                }
                
                $len = count($columns);

                if($len == count($keys)) {
                    for($i = 0; $i < $len; $i++) {
                        if($i < $len - 1) {
                            $tableCols .= "$columns[$i],";
                            $placeHolders .= ":$keys[$i],";
                        }
                        else {
                            $tableCols .= "$columns[$i]";
                            $placeHolders .= ":$keys[$i]";
                        }
                    }

                    $insertQuery = "INSERT INTO $this->DB_NAME.$tablename($tableCols)".
                                   " VALUES($placeHolders)";
                    print($insertQuery);
                    $insert = $this->pdo->prepare($insertQuery);
                    $result = $insert->execute($values);
                }
                else {
                    throw new Exception("Error in Dbconnect->insert method: number of key values does not match table columns", 1);
                }
            }
            else {
                throw new Exception("Error in Dbconnect->insert method: array not given for prepared statement", 1);
            }
            return $result;
        }
        catch(PDOException $e) {
            throw new Exception($e->getMessage());
            die();
        }

    }

    public function update($items, $query) {
        try {
            foreach($items as $item) {
                trim($item);
            }
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute($items);
        }
        catch(PDOException $e) {
            addError($e->getMessage(), get_class($this), "deleteItem");
            die();
        }
    }

    public function deleteItem($itemId, $tablename, $changeVal) {
        try {
            $alterFlag = $this->pdo->prepare("UPDATE $this->DB_NAME.$tablename ".
                    "SET deleted='$changeVal'".
                    "WHERE id = $itemId");
            $alterFlag->execute();
        }
        catch(PDOException $e) {
            die(addError($e->getMessage(), get_class($this), "deleteItem"));
        }
    }

    public function __destruct() {
        $this->pdo = null;
    }
}

class Session {
    public function __construct() {
        addError("session starting");
        session_start();
    }

    public function sessionSet($postValues, $database) {
        $fingerprint = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
        //$query = "SELECT :username, :role FROM gamesite.username"
        if(isset($_POST['login'])) unset($_POST['login']);
        $queryKeys = $_POST;//array('username' => $postValues['username']);
                           
        $userInfo = $database->retrieve("userlogin", $queryKeys, true);
        print_r($userInfo);
        $_SESSION['valid_user'] = $userInfo['username'];
        $_SESSION['role'] = $userInfo['role'];
        $_SESSION['last_activity'] = time();
        $_SESSION['fingerprint'] = $fingerprint;
    }   

    public function logOut() {
        // session_unset();
        addError("Logging out");
        if(isset($_SESSION['last_activity']) || isset($_SESSION['fingerprint'])) {
            addError("SESSION destruction");

            setcookie(session_name(), '', time()-61200,'/');
            session_destroy();  
            unset($_SESSION);
        }
    }

    public function sessionActive() {
        addError("Checking session activity");
        $fingerprint = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
        print($_SESSION['last_activity']);
        if(isset($_SESSION['last_activity'])) {
            addError("Session fingerprint ". $_SESSION['last_activity'] . " and " . $fingerprint);
        }
        
        if((isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) 
        || (isset($_SESSION['fingerprint']) && $_SESSION['fingerprint'] == $fingerprint)
        ) {
            session_regenerate_id();
            $_SESSION['last_activity'] = time();
            $_SESSION['fingerprint'] = $fingerprint;
            addError("**** SESSION REFRESHED ****");
            return true;
        }
        else {
            addError("Session destroyed");
            $this->logOut();
            return false;
        }
    }

    public function __destruct() {
    }
}

function checkSession() {
    session_start();
    $fingerprint = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
    $_SESSION['last_active'] = time();
    $_SESSION['fingerprint'] = $fingerprint;
    if(!isset($_SESSION['email_address'])) {
        $serverUri = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."/login.php";
        header("Location: ".$serverUri);
    }
}

function sessionSet($userId, $role) {
    $_SESSION['valid_user'] = $userId;
    $_SESSION['role'] = $role;
    $_SESSION['last_active'] = time();
}

function checkSSL() {
    if($_SERVER[REQUEST_SCHEME] == "http") {
        $serverUri = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        //header("HTTP/1.1 301 Moved Permanently");
        header("Location: ".$serverUri);
        exit();
    }
}

function redirect($location) {
    $serverUri = substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], "/"));
    $serverUri = $serverUri."/$location"; // add https request
    
    header("Location: ".$serverUri);
    exit();
}


function setSearchCookie($item) {
    $month = time() + (3600 * 24 * 30);
    //if($item != null) {
    $success = setcookie("search", $item, $month);
    //}
}

function getPreviousSearch() {
    addError("CALLING return Cookie value " . isset($_COOKIE['search']));
    if(isset($_COOKIE['search'])) {
        return $_COOKIE['search'];
    }
    else {
        return "id";
    }
}

function toHtml($string) {
    echo htmlspecialchars($string);
}
?>










