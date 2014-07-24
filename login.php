<?php
/**
 * Created by Ragu S.
 * Date: 07/23/2014
 * Time: 2:21 AM
 * for coding standards follow PEAR, see sample file on the site
 */

require("library.php");


?>
<!DOCTYPE html>
<html>
<head>
	<title> Login Page </title>
	<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
	<link rel="stylesheet" href="styles/stylesheet.css" />
	<script type="text/javascript" src=""></script>
</head>
<body>
	<div class="page">
		<div class="content">
			<div class="login">
				<form action="login.php" method="post">
					<table>
						<header>Login</header>
						<tr>
							<td>
								Username:
							</td>
							<td>
								<input type="email" />
							</td>
						</tr>
						<tr>
							<td>
								Password:
							</td>
							<td>
								<input type="password" />
							</td>
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
	</div>
</body>
</html>

