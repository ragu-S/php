<?php
/**
 * Created by Ragu S.
 * Date: 07/23/2014
 * Time: 2:21 AM
 * for coding standards follow PEAR, see sample file on the site
 */

require("library.php");
//$messages = array();

$session = new Session();

if($session->sessionActive()) {
	addError("Session Active");
	//Session was active and user had been logged in, thus redirect to main page
	redirect("addItem.php");
}

if($_POST) {
	if(isset($_POST['register'])) {
		redirect("registerUser.php");
		//header("Location: registerUser.php");
	}
	// Sets validation requirements with regex and creates user information varaibles
	$login = new Login();
	
    // Make database connection
	$data = new dbConnect();

	// Call validation on username field and password field
	//$userValid = $username->validateFormInput();
	//$passwordValid = $password->validateFormInput();
	if($login->loginValidate()) {
		addError("Validation true");
		$session->sessionSet($_POST, $data);
		$session->logout();
		//redirect("addItem.php");
		// build query to compare user name and password stored in database
        // if($login->isUserRegistered($data)) {
        // 	// method sessionSet(username, role)
        // 	// Set session for validated user
        // 	Adderror($login->getUser()." | ".$login->getRole());
        // 	$session->sessionSet($login->getUser(), $login->getRole());
        // 	redirect("login.php");
        // }
        // addError("function had not redirected");
		// Authentication shld be done outside of class	
	}
	else {
		addError("function had returned false");
	}
}

?>
<!DOCTYPE html>
<html>

<head>
	<title> Login Page </title>
	<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
	<link rel="stylesheet" href="styles/style.css" />
	<script type="text/javascript" src=""></script>
</head>
<body>
	<div class="page">
			<div class="login">
				<form action="login.php" method="post">
					<table class="loginTable">
						<tr>
							<th colspan="2">
								Login		
							</th>
						</tr>
						<tr>
							<th colspan="2">
								Already have an account? <a href="#">Log in</a>		
							</th>
						</tr>
						<tr>
							<td>
								Username:
							</td>
							<td>
								<input type="text" name="username" value=""/>
							</td>
							
						</tr>
						<tr>
							<td>
								Password:
							</td>
							<td>
								<input type="password" name="password" value=""/>
							</td>
						</tr>
						<tr>
							<td>
								<input type="submit" name="login" value="Login" />
							</td>
							<td>
								<input type="submit" name="register" value="Register"/>
							</td>
						</tr>
						<?php if($_POST) $login->showFormErrors(); ?>
					</table>
				</form>
			</div>
	</div>
	<div class="additionalNotes">
		<ul>
			<h1> For user registration </h1>
			<li class="toComplete">
				Form field highlighting in red
			</li>
			<li class="toComplete">
				Password reenter field
			</li>
			<li class="toComplete">
				Password strength hint meter
			</li>
			<li class="toComplete">
				Ajax feedback (ie. is current username taken)
			</li>
			<li class="toComplete">
				add helpful tool tups (ie. popup boxes)
			</li>
			<li class="toComplete">
				required fields shld have *
			</li>
			<li class="toComplete">
				Connect with Facebook or Twitter
			</li>
		</ul>
	</div>
	<?php
		displayError();
	?>
</body>
</html>

