<?php
	require_once("library.php");

	$session = new Session();

	if($session->sessionActive()) {
		addError("Session Active USER LOGGED IN");
		//$session->logOut();
		//Session was active and user had been logged in, thus redirect to main page
		//header();
	}
	else {
		addError("**************** Logout *********************");
		// Send user to login page again
		// redirect("login.php");
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
<?php
if($_POST) {
	$register = new Login();
	$data = new dbConnect();
	$register->validate();
	$register->registerUser($data);
	// if($register->validate()) {

	// 	addError("Validation true");
	// }
	// else {
	// 	addError("function had returned false");
	// }
}

?>
<body>
<div class="page">
	<div class="content">
		<form action="registerUser.php" method="post">
			<table>
				<tr>
		            <td>Email address:</td>
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
		        <?php if($_POST) $register->showError(); ?>
			</table>
		</form>
	</div>	
</div>
<?php
		displayError();
	?>
</body>
</html>