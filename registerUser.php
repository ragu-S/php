<?php
	require_once("library.php");

	$session = new Session();

	if($session->sessionActive()) {
		addError("Session Active USER LOGGED IN");
		redirect("sampleView.php");
		//$session->logOut();
		//Session was active and user had been logged in, thus redirect to main page
		//header();
	}
	else {
		addError("**************** Logout *********************");
		// Send user to login page again
		//redirect("login.php");
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title> Register </title>
	<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
	<link rel="stylesheet" href="styles/style.css" />
	<script type="text/javascript" src=""></script>
</head>
<?php
if($_POST) {
	$register = new Login();
	$data = new dbConnect();
	if($register->loginValidate()) {
		addError("FORM IS VALID");
	}
	
	if($register->registerUser($data)) {
		addError("Validation true");
		redirect("login.php");
	}
	
}

?>
<body>
<div class="page">
	<div class="content">
		<form action="registerUser.php" method="post">
			<table>
				<tr>
		            <td>Username (Enter email address):</td>
		            <td><input name="username" type="text" value=""></td>   
		        </tr>
		        <tr>
		        	<td>Password:</td>
		        	<td><input type="password" name="password" value=""></td>
		        </tr>
		        <tr>
					<td>
						Password Hint:
					</td>
					<td>
						<input name="passwordHint" type="text" >
					</td>
				</tr>
				<tr>
		        	<td>Role</td>
		        	<td>
		        		<select name="role">
		                    <option value="user">User</option>
		                    <option value="admin">Admin</option>
		                </select>
		        	</td>
		        </tr>
		        <tr><td><br></td></tr>
		        <tr>
		            <td><input name="" type="submit" value="Submit"></td>
		            <td><input name="" type="reset" value="Clear"></td>
		        </tr>
		        <?php if($_POST) $register->showFormErrors(); ?>
			</table>
		</form>
	</div>	
</div>
<?php
		displayError();
	?>
</body>
</html>