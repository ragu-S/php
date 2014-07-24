<?php
// library.php
// Contains all class declerations

function assertT($parm) {
    if(strlen($parm)) {
        $output = '<script>console.log("#");</script>';
        $output = str_replace("#", $parm, $output);
        print $output;
    }
}

// class RadioButton extends ItemEntry
// class TextField extends ItemEntry
// class SelectOption extends ItemEntry
// class CheckBox extends ItemEntry
// class TextArea extends ItemEntry
class ItemEntry {
    protected $_name;
    protected $_regEx;
    protected $_error;
    protected $_valid;
    protected $_set;
    protected $_type;
    protected $_value;

    public function __construct($name, $type = null, $regEx=null) {
        if(is_string($name) && is_string($type)) {
            $this->_name = $name;
            $this->_type = $type;
        }
        $this->_regEx = $regEx;
        $this->_error = "Invalid Entry: " . $this->_name . " must be selected";
        $this->_set = false;
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
    public function validate($value=NULL) {
        //$valid = true;
        $invalid = array();
        $this->_error ="Invalid Entry: " . $this->_name;
        //if($value !== NULL && $this->_set) {
            //assertT("!Value: ".strlen($value));
            if(($this->_type === "text") && $this->_regEx !== NULL) {
                if(strlen($value) && !preg_match($this->_regEx, $value, $invalid)) {
                    $this->_error = false;
                    $this->_valid = true;
                }
                else {
                    if(count($invalid)) {
                        $this->_error .= " cannot have $invalid[0]";
                    }
                    else {
                        $this->_error .= " cannot be empty (by regex)";
                    }
                }
            }
            elseif($this->_type === "select") {
                if($value !== "--Please choose--") { // check if default is set
                    $this->_valid = true;
                    $this->_error = false;
                }
                else {
                    $this->_valid = false;
                    $this->_error .= " size must be checked";
                }
            }
            elseif($this->_type === "radio") {

            }
            else {
                $this->_error .= " cannot be empty (by normal not NULL check)";
            }
        //}
        // else {
        //     $this->_error .= " cannot be empty (by normal check)";
        //     //$this->_valid = false;
        // }
        //assertT($this->_error);
        return $this->_valid;
    }
    // public function displayItem() {
    //     if(isset($_POST[$this->_name]) && $this->isValid()) {
    //         return $_POST[$this->_name];
    //     }
    //     elseif(isset($_GET[$this->_name]) && $this->isValid()) {
    //         return $_GET[$this->_name];
    //     }
    // }
    // public function showFormError() {
    //     if($_POST && $this->isValid()) {
    //         echo "<td class=\"form_error\">".$this->getError()."</td>";
    //     }
    // }
}

interface FormItemMethods {
    function validation();
    function validateItem();
}

// class RadioButton extends ItemEntry
class TextField extends ItemEntry implements FormItemMethods {
    public function __construct($name, $regEx = null) {
        ItemEntry::__construct($name);
        if($regEx !== null)
            $this->_regEx = $regEx;
    }
    public function validation() {
        ;
    }
    public function validateItem($formValue = null) {
        $invalid = array();
        //if($formValue !== null) {
            if($this->_regEx !== null) {
                if(preg_match($formValue, $this->_regEx, $invalidChars)) {
                    if(count($invalidChars)) {
                        $this->_error .= " cannot have $invalid[0]";
                    }
                    else {
                        $this->_error .= " cannot be empty";
                    }
                }
                else {
                    $this->_error = false;
                    $this->_valid = true;
                }
            }
            else {
                $this->_error = "Remove msg later: Error no regex set for " . $this->_name;
            }
        //}
    }
    public function __destruct() {}
}

// class TextField extends ItemEntry
// class SelectOption extends ItemEntry

            // elseif($this->_name === "tShirt") {
            //     if($value !== "--Please choose--") {
            //         $this->_valid = true;
            //         $this->_error = false;
            //     }
            //     else {
            //         $this->_valid = false;
            //         $this->_error .= " size must be checked";
            //     }
            // }

// class CheckBox extends ItemEntry
// class TextArea extends ItemEntry

class FormUserRegisteration {
    // attributes of form UserRegisteration class
    // Radio buttons
    private $title;

    //Text Input fields
    private $firstName;
    private $lastName;
    private $organization;
    private $email;
    private $phone;

    //Checkboxes
    private $monday;
    private $tuesday;

    //Select Option
    private $tShirt;

    //generic Form Element
    private $formElement;

    public function __construct() {
        //Radio
        $this->title = new ItemEntry("title", "radio");
        $this->title->setRegEx("/([^mr]|[^mrs]|[^ms])/i");

        //Text Fields
        $this->firstName = new ItemEntry("firstName");
        $this->firstName->setRegEx("/(\s{20}|[^a-z\-;\:\,\'0-9 \s])/i");
        $this->lastName = new ItemEntry("lastName");
        $this->lastName->setRegEx("/(\s{20}|[^a-z\-;\:\,\'0-9 \s])/i");
        $this->organization = new ItemEntry("organization");
        $this->organization->setRegEx("/(\s{20}|[^a-z\-;\:\,\'0-9 \s])/i");
        $this->email = new ItemEntry("email");
        $this->email->setRegEx("/(\s{20}|[^a-z\-;\:\,\'0-9 \s])/i");

        $this->phone = new ItemEntry("phone");
        $this->phone->setRegEx("/([^0-9- ]|[^0-9]{9})/");

        //Checkboxes
        $this->monday = new ItemEntry("monday");
        $this->tuesday = new ItemEntry("tuesday");
        
        //Select Option
        $this->tShirt = new ItemEntry("tShirt");
        $this->tShirt->setRegEx("/--Please choose--/");
        $this->formElement = array();
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
    public function __get($itemName) {
    	   return $this->$itemName->getItem("_name");
    }
    public function hasRegEx($item) {
        return $this->$item->getItem("_regEx") !== NULL;
    }
    public function addOption($item) {
        //print_r($item);
        foreach($item as $entry)
            echo makeTag($entry, "option");
    }
    public function displayItem($formName) {
        if(isset($_POST[$formName]) && !$this->$formName->isValid()) {
            echo $_POST[$formName];
        }
        elseif(isset($_GET[$formName]) && !$this->$formName->isValid()) {
            echo $_GET[$formName];
        }
        //echo $this->$formName->displayItem();
    }
    public function validateForm() {
        $index = count($_POST);
        $formValid = true;
        for($i = $index; ($i > 0); $i--) {
            $element = current($_POST);
            $key = key($_POST);
            assertT($key . " : " . $element);
            if($key !== "submit")
                $formValid = $this->$key->validate($element);
            next($_POST);
        }
        if(!isset($_POST["title"]))
        //$this->title->validate();
        if(!$formValid) {
            // display error;
            //$this->$postName->
        }
        return $formValid;
    }
    public function showFormErrors($formName) {
        //$errorMsg = $this->$formName->getError();
        //assertT("Error in ".$formName." ".$errorMsg);
        if($_POST && !$this->$formName->isValid()) {
            echo "<td class=\"form_error\">".$this->$formName->getError()."</td>";
        }
    }
    public function storePostValues() {
        $index = count($_POST);
        $check = true;
        assertT("calling storePostValues $index"); 
        $key = "firstName";

        // Primary purpose for this class method is to input values into the userFormClassMain class

        for($i = $index; ($i > 0) && $check; $i--) {
            $element = current($_POST);
            $key = key($_POST); 
                   
            if(strcmp($key, "submit")){// && $this->$key->isValid()) {
                //assertT($this->$_key->getItem("_regEx"));
                assertT($this->$key->getItem("_name") . " : " . $this->$key->getItem("_regEx"));
                $this->makeFormElement($this->$key->getItem("_name"), $this->$key->getItem("_RegEx"));
                //makeFormElement($key, )
                //setFormValue$key
            }
            else {
                $this->getFormElement();
                $check = false;                
            }
            next($_POST);
        }
    }
    // destructor to clean up any code 
    function __destruct() {
    }
}


// class databaseObject {

// }

function readAuthenticateFile() {
	//$fp = fopen("./topsecret.txt", "r+");
	$error = false;
	$connectionInfo = null;
	//if(flock($fp, LOCK_EX) && 
	if($connectionInfo = file("./topsecret.txt")) {
		$error = false;
		//preg_match("/(?<=: ).*$/i", $connectionInfo);
	}
	else {
		error_log("unable to open topsecret file for connection", 0);
		$connectionInfo = null;
	}
	return $connectionInfo;
}


// private $authenticate;
// private $database;
// private $insert;

function openConnection() { // constructor public function __construct
	if($this->authenticate = readAuthenticateFile()) {
		try {
			$this->database = new PDO($authenticate[0], $authenticate[3], $authenticate[1], $authenticate[2]);
			$this->dbName = $authenticate[3];
		}
		catch (Exception $error) {
			//die("Connection failed: " $error->getMessage());
		}
	}
	else {
		error_log("unable to open topsecret file for connection", 0);
		assertT("error with file read in authenticate");
	}
}

function prepareQueryStatements($tablename) {
	$this->insert = $this->database->prepare("INSERT INTO $this->dbName\.$tablename".
			 "(title, firstName, lastName, organization, email, phone, tShirt)".
			 "VALUES (:title, :firstName, :lastName, :organization, :email, :phone, :tShirt)"); 
}

function insertItem() { // ensure all postback values are valid
	if($_POST)
		$this->insert->execute($_POST);
	elseif($_GET)
		$this->insert->execute($_GET);
}

?>


<!-- CREATE TABLE user 
(
    userId int zerofill not null auto_increment,
    title varchar(3) not null,
    firstName varchar(25) not null,
    lastName varchar(25) not null,
    organization varchar(30),
    emailAddress varchar(50),
    phoneNumber int,
    monday boolean,
    tuesday bool,
    tShirt varchar(2),
    primary key (userId)
); -->