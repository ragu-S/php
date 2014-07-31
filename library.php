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
    $input = htmlspecialchars($input);
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
        <div class="menu">
            <nav>
                <ul>
                    <li><a href="addItem.php">Add</a></li>
                    <li><a href="sampleView.php">View All</a></li>
                    <!-- <li><a href=""> </a></li> -->
                    <?php 
                    ?>
                </ul>
            </nav>
        </div>
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
        foreach($this->_menuItems as $item) {
                ?>
                <li><a href="<?php echo $item['path']; ?>"><?php echo $item['link'];?></a></li>    
                <?php
        }
    }
    public function searchBox() {

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

    public function __construct($name, $validate, $whitelistValidation = false) {
        if(is_string($name)) {
            $this->_name = $name;
        }

        addError("constructor called", $this->_name, "contruct");
        // default textfield regEx
        
        $this->_whitelist = $whitelistValidation;
        $this->_regEx = "/(^\s*$)|([^a-z\-;\:\,\'0-9\s])/i";
        $this->_error = "Invalid Entry: this field ";
        $this->_validateField = $validate;        
        $this->_valid = false;
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
        if(isset($_POST[$this->_name]) && !$this->_valid) {
            return $_POST[$this->_name];
        }
    }

    // Used for showing errors in form entry
    public function showFormErrors() {
        if($_POST && !$this->_valid) {
            ?>
                <td class="error"> <?php echo $this->getError();?></td>
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
        $this->_regExUser = "/(^[0-9a-z]+@[a-z]+\.[a-z]+$)/i";
        $this->_regExPassword = "/(^\s*$)|(^[0-9a-z!@#$%^&*()]{1,8}$)|([^0-9a-z!@#$%^&*()])/i";
        
        $this->_formValid = false;
    }
    public function getUser() {
        return $this->_username;
    }
    public function getRole() {
        return $this->_role;
    }
    public function validate() {
        if(isset($_POST['username']) && isset($_POST['password'])) {
            // Set form valid to true since we are performing blacklist checking, ie. looking for invalid input
            $this->_formValid = true;

            if(!preg_match($this->_regExUser, $_POST['username']) && preg_match($this->_regExPassword, $_POST['password'])) {
                $this->_formValid = false;
                $this->_error = "Invalid username or password";
            }
            
            if($this->_formValid) {
                // Remove special characters if they exist (as a precaution)
                $this->_username = removeSpecialChars($_POST['username']);
                $this->_password = removeSpecialChars($_POST['password']);

                // encrypt password with salt $1$1p0rHF1b$    
                $this->_password = crypt($_POST['password'], "$1$1p0rHF1b$");   
            }
        }
        else {
            $this->_error = "Username or password cannot be empty";
            $this->_formValid = false;
        }
        
        return $this->_formValid;
    }
    public function showError() {
        if($_POST && !$this->_formValid && $this->_error != false) {
            ?>
            <tr>
                <td class="error" colspan="2">
                    <?php echo $this->_error; ?> 
                </td>
            </tr>
            <?php
        }
    }
    public function isUserRegistered($database) {
        if($database->authenticate($this->_username, $this->_password)) {
            addError("Username and password are already taken");
            $this->_error = "Username and password are already taken";
            $this->_formValid = false;
        }
        else {
            $this->_formValid = true;
        }
        return !$this->_formValid;
    }
    public function registerUser($database) {
        $registered = false;
        var_dump($_POST);
        // if Role and password hint had been given, sanitize the input and store as values
        if(isset($_POST['passwordHint'])) {
            if(!preg_match("/^[a-z0-9]+$/i", $_POST['passwordHint'])) {
                if($this->_formValid) {
                    $this->_error = "Password hint is required";
                }
                $this->_formValid = false;
            }
            else {
                $this->_role = $_POST['role'];
                $this->_passwordHint = removeSpecialChars($_POST['passwordHint']);
                $this->_formValid = true;
            }

        }
//'boyczuk@senecacollege.ca',  hallgkdls,'$1$1p0rHF1b$iDaVRxMxTTk8qIF8baTw21'       
        if($this->_formValid) {
            if(!$this->isUserRegistered($database)) {
                addError("Inserting to user table");
                $query = "INSERT INTO gamesite.userlogin".
                         "(username, password, role, passwordHint)".
                         " VALUES('$this->_username', '$this->_password', '$this->_role', '$this->_passwordHint')";
            
                $registered = $database->insertUser("userlogin", $query);
            }
            else {
                $registered = false;
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
        if(is_string($name)) {
            $this->_name = $name;
        }
        $this->_validateField = $validate;        
        $this->_error = "This field must be checked";
        $this->_valid = true;
    }
    public function validate() {
        if(!isset($_POST[$this->_name])) {
            $_POST[$this->_name] = "n";
            // Check if we are required to validate this checkbox field
            if($this->_validateField) {
                $this->_valid = false;
            }
        }
        else {
            $_POST[$this->_name] = "y";    
        }
        addError("Checkbox Validation END ".$this->_valid);
        return $this->_valid;
    }

    public function showFormErrors() {
        if(isset($_POST[$this->_name]) && !$this->_valid) {
            ?>
                <td class="error"> <?php echo $this->_error;?></td>
            <?php
        }
    }

    public function displayItem() {
        if(isset($_POST[$this->_name]) && !$this->_valid) {
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

class SearchItem extends ItemEntry {
    // private $_value;
    // private $_regEx;

    public function __construct($formName) {
        ItemEntry::__construct($formName);
        $this->_regEx = "/(^\s*$)|([^a-z0-9\.\,\'\"\- \s])/i";
    }

    public function validate() {
        /**
         * This function validates the Search term for invalid characters based on the Textarea field validation
         */        
        if(isset($_POST['searchText']) && ItemEntry::validate($_POST['searchText'])) {
            // Sanitize input value for illegal characters
            $_POST['searchText'] = removeSpecialChars($_POST['searchText']);
        }
        else {
            $this->_error = "Search entry invalid";
        }
        return $this->_valid;
    }

    public function search($database) {
        $query = "SELECT * FROM inventory ".
                 "WHERE description LIKE \"%$this->_value%\"";

        return $database->retrieveRow("inventory", $query);
    }
}

class FormItemEntry {
    // attributes of form Item Entry class

    // Text Input fields
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
        // ItemEntry(<input field name>, <validate>, <positive validate>)

        //Text Field
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
        $this->reorder = new ItemEntry("reorder", true, false);
        $this->reorder->setRegEx("/[^0-9]/");

        //Checkboxes
        $this->backOrder = new CheckBox("backOrder", false);
    }
    public function makeFormElement($name, $regEx = null) {
        $formItem = new ItemEntry($name);
        $formItem->setRegEx($regEx); 
        array_push($this->formElement, $formItem);
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
        $this->_formValid = 0;

        if($_SERVER["REQUEST_METHOD"] == "POST") { // or better $_SERVER["REQUEST_METHOD"] == "POST"
            if(isset($_POST['submit'])) {
                unset($_POST['submit']);
            }
            foreach($_POST as $key=>$value) {     
                if(!$this->$key->validate()) {
                    $this->_formValid++;
                    // addError("FormName: ".$key." Value: ".$value);            
                }     
            }
            addError("is form valid before Checkboxes?: " . $this->_formValid);
            // Validate Checkboxes
            if(!$this->backOrder->validate()) {
                $this->_formValid++;
            }
            addError("is form valid AFTER ?: " . $this->_formValid);
            // Set deleted to no for initial entry
            $_POST['deleted'] = "n"; 

        }
        var_dump($_POST);
        
        print($this->_formValid);
        return ($this->_formValid > 0) ? false : true;
    }
    public function showFormErrors($formName) {
        echo $this->$formName->showFormErrors();
    }
    public function storeValues() {
        // Primary purpose for this class method is to input values into the userFormClassMain class
        // if($this->_formValid) {
        //     $this->formElement = array();
        //     if($_POST) {
        //         foreach($_POST as $key=>$value) {     
        //             $formElement[$key] = trim($value);
        //         }
        //     }
        //     elseif($_GET) {
        //         foreach($_GET as $key=>$value) {     
        //             $formElement[$key] = trim($value);
        //         }
        //     }
        // }
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

    public function retrieveRow($tablename, $query = null) {
        try {
            if($query == null) {
                $query = "SELECT * FROM $this->DB_NAME.$tablename";
            }
            addError("QUery is ".$query);
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            return $stmt->fetch();
        }
        catch(PDOException $e) {
            die(addError($e->getMessage(), get_class($this), "retrieveRow"));
        }
    }
    public function retrieveAllRows($tablename, $query = null) {
        try {
            if($query == null) {
                $query = "SELECT * FROM $this->DB_NAME.$tablename";
            }
            
            $stmt = $this->pdo->prepare($query);
            $result = $stmt->execute();
            addError("Result is $result");
            return ($result > 0) ? $stmt->fetchAll() : !!$result;
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

    public function authenticate($username, $password) {
        $query = "SELECT username, password, role FROM gamesite.userlogin".
                     " WHERE username = '" . $username . "'".
                     " AND password = '". $password. "'";
            
        $queryResult = $this->retrieveRow("userlogin", $query);
           
        return isset($queryResult['username']);    
    }

    public function insertItems($inputArray, $tablename, $insertQuery = null) {
        $colVals = "";
        if($insertQuery == null)   {
            $insertQuery = "INSERT INTO $this->DB_NAME.$tablename".
                           "(itemName, description, supplierCode, cost, price, onHand, reorderPoint, backOrder, deleted) "; 
            
            foreach($inputArray as $key => $value) {
                $colVals .= ":$key,";
            }
            $insertQuery .= "VALUES(";
            $insertQuery .= substr($colVals, 0, strlen($colVals)-1);
            $insertQuery .= ")";
            
        }
        $insert = $this->pdo->prepare($insertQuery);
        if(count($inputArray)) {
            // trim input before submitting to database
            // foreach($inputArry as $key=>$value) {
            //     trim($inputArry[$key]);
            // }
            //print_r($inputArry);
            try {
                $insert->execute($inputArray);
            }  
            catch(PDOException $e) {
                die(addError($e->getMessage(), get_class($this), "insertItems"));
            }
        }
        else {
            addError("POST array was empty ", get_class($this), "insertItems");
        }
    }
    public function insertUser($tablename, $query) {
        try {
            $insert = $this->pdo->prepare($query);
            //return ($insert->execute() === 0) ? false : true;
            $result = $insert->execute();
            addError("Insert User yields ".$result);
            return !!$result;
        }
        catch(PDOException $e) {
            die(addError($e->getMessage(), get_class($this), "insertUserss"));
        }
    }
    public function selectItems($itemId, $tablename, $selectQuery = null) {
        
        $select = $this->pdo->prepare("SELECT * FROM $this->DB_NAME.$tableName");
        $select->execute();
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
    //private $userId;
    private $validated;

    public function __construct() {
        // session_name('userRegister');
        addError("session starting");
        session_start();
        addError($_SESSION['last_activity']);
        addError(session_name());
    }
    public function sessionSet($userId, $role) {
        $fingerprint = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
        //session_start();
        $_SESSION['valid_user'] = $userId;
        $_SESSION['role'] = $role;
        $_SESSION['last_activity'] = time();
        $_SESSION['fingerprint'] = $fingerprint;
    }
    public function logOut() {
        // session_unset();
        addError("Logging out");
        if(isset($_SESSION['last_activity']) || isset($_SESSION['fingerprint'])) {
            addError("SESSION destruction");
            
            //unset($_SESSION['valid_user']); //(isset($_SESSION['valid_user']) && 
            //unset($_SESSION['role']); //(isset($_SESSION['role']) && 
            //unset($_SESSION['last_activity']); //(isset($_SESSION['last_activity']) && 
            //unset($_SESSION['fingerprint']); //(isset($_SESSION['fingerprint']) && 
            
            setcookie(session_name(), '', time()-61200,'/');
            session_destroy();  
            unset($_SESSION);
        }
    }
    private function unsetSession() {
        unset($_SESSION['valid_user']); //(isset($_SESSION['valid_user']) && 
        unset($_SESSION['role']); //(isset($_SESSION['role']) && 
        unset($_SESSION['last_activity']); //(isset($_SESSION['last_activity']) && 
        unset($_SESSION['fingerprint']); //(isset($_SESSION['fingerprint']) && 
    }
    public function sessionActive() {
        addError("CHecking session activity");
        $fingerprint = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
        if(!isset($_SESSION['last_activity'])) {
        //   session_start();
        }
        //session_start();
        if($_SESSION['last_activity']) {
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
?>










