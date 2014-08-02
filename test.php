<?php
require_once("library.php");

$session = new Session();
//checkSSL();
//print_r($_SERVER);
//$session->sessionSet("ragu", "dev");
$html = new html();
$menu = new Menu();
$html->htmlHead();
$html->htmlBody();



//$session->logOut();
var_dump($_SESSION);
print("********************");
$session->sessionActive();
var_dump($_SESSION);
addError(session_name());
addError($_SESSION['valid_user']);
addError($_SESSION['role']);
addError($_SESSION['last_activity']);
addError($_SESSION['fingerprint']);

displayError();

// $session->logout();
$session->logOut();
// session_name();
addError(session_name());
//var_dump($_SESSION);
// addError($_SESSION['valid_user']);
// addError($_SESSION['role']);
// addError($_SESSION['last_activity']);
// addError($_SESSION['fingerprint']);
// addError($_SESSION['valid_user']."*****");
displayError();
//phpinfo();

// Specialized error msg handeling
// foreach($this->_regEx as $validation) {
//     if(preg_match($validation["regEx"], $_POST[$this->_formName], $invalidChars)) {
//         //$this->_error = "This field " . $validation['errMsg'];
//         $this->_formValid = false;
//     }
// }

// public function setRegex($regEx, $errMsg = "", $evalTrue = true) {
//     // Set up a validation rule based on regex, as well as message to display
//     $validation = ["regEx" => $regEx, "errMsg" => $errMsg];

//     // Check if we have defined _regEx property as 
//     if(!is_array($this->_regEx)) {
//         //$this->_regEx = array();
//     }

//     // push validation rule into _regEx property
//     array_push($this->_regEx, $validation);
// }
?>