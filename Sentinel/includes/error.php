<style>
@import url('https://fonts.googleapis.com/css2?family=Golos+Text:wght@500&family=Roboto:wght@100&display=swap');
</style> 


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
	<title>Error</title>
	<link rel="stylesheet" type="text/css" href="../style/style.css">
</head>
<body>
<div class="wrapper"  >

	<div class="form">
	<h1>Error</h1>
		<?php
		// Check if an error code is present in the URL
		if (isset($_GET["error"])) {   //One page sends another to this one and dependant on the switch case will return a different result based on the error
			$errorCode = $_GET["error"];
			// Display different error messages based on the error code
			switch ($errorCode) {
				case "usernameexists":
					echo "<p>The username you entered already exists. Please choose a different username.</p>";
					break;
				case "sqlerror":
					echo "<p>There is a database error. Please try again later..</p>";
					break;
				case "invalidusernameorpassword":
						echo "<p>Invalid username or password.</p>";
						break;
				default:
					echo "<p>An error has occurred. Please try again later.</p>";
					break;
			}
		} else {
			echo "<p>An error has occurred. Please try again later.</p>";
		}
		?>
		<h1><img src="../images/error.png" alt="error" width="200"></h1> <!--My beautiful error image-->
		<p>Click <a href="../index.html">here</a> to return to login.</p>
	</div>
</div>
<footer>
	<div class="footer">
		<p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sentinel <br> Andrew Gilbey© 2023 </p>
	</div>
</footer>
</body>
</html>
