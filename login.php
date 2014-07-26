<?php
/**
 * Created by Ragu S.
 * Date: 07/23/2014
 * Time: 2:21 AM
 * for coding standards follow PEAR, see sample file on the site
 */

require("library.php");

if($_POST) {
	$username = new Login("username");
	$password = new Login("password");

	$username->validate();
	//$logUser->
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
								<input type="reset" value="Clear"/>
							</td>
						</tr>
					</table>
				</form>
			</div>
	</div>
</body>
</html>

