<?php
/**
 * Created by Ragu S.
 * Date: 07/23/2014
 * Time: 2:21 AM
 * for coding standards follow PEAR, see sample file on the site
 */

require("library.php");
$messages = array();

$session = new Session();

if($session->sessionActive()) {
	// Session was active and user had been logged in, thus redirect to main page
	//header();
}

if($_POST) {
	$username = new Login("username");
	$password = new Login("password");

	// Set validation requirements with regex
    $username->setRegex("/([^0-9a-z!@#$%^&*()])/i", "cannot contain invalid characters");

	$password->setRegex("/(^[0-9a-z!@#$%^&*()]{0,7}$)/i", "cannot have fewer than 8 characters");
    $password->setRegex("/([^0-9a-z!@#$%^&*()])/i", "cannot contain invalid characters");

    // Make database connection
	$data = new dbConnect();

	// Call validation on username and password
	if($username->validateFormInput() && $password->validateFormInput()) {
		array_push($messages, "function returns true");
		print_r($username->authenticate($data));
		// if($username->authenticate($data)) {
		// 	array_push($messages, "user authenticated");
		// 	// store session
		// 	if($session->sessionActive()) {
		// 		array_push($messages, "session is being Set");
		// 		sessionSet($userId, 'user');
		// 		//$session->sessionSet($userId, 'user');
		// 		//header("Location: addItem.php");
		// 	}
		// }
		array_push($messages, "header not sent");
		// Authentication shld be done outside of class	
	}
	else {
		//array_push($messages, "function had false return");
		//addError($err, $class = null, $method = null)
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
								<input type="text" name="username" />
							</td>
							<?php if($_POST) $username->showFormErrors(); ?>
						</tr>
						<tr>
							<td>
								Password:
							</td>
							<td>
								<input type="password" name="password" />
							</td>
							<?php if($_POST) $password->showFormErrors(); ?>
						</tr>
						<tr>
							<td>
								<input type="submit" value="Submit" />
							</td>
							<td>
								<input type="submit" value="Register"/>
							</td>
						</tr>
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
		foreach($messages as $message) {
			assertT($message);
		}
		displayError();
	?>
</body>
</html>

