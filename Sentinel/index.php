<style>
@import url('https://fonts.googleapis.com/css2?family=Golos+Text:wght@500&family=Roboto:wght@100&display=swap');
</style> 

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Login Page</title>
	<link rel="stylesheet" type="text/css" href="style\style.css">
</head>
<body>
	<div class="wrapper">
		<div class="form" >
			<img src="images/sent-logo.png" alt="Sentinel" width="200">
            <br><br>
			<form action="includes/login.inc.php" method="POST">
			<label for="username">Username:</label>
			<input type="text" id="username" name="username" required>
			<label for="password">Password:</label>
			<input type="password" id="password" name="password" required>
			<button type="submit">Login</button>
			<a href="signup.php"><button type="button">Register</button></a>
		</form>
		</div>
	</div>
	<div class="footer">
		<p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sentinel <br> Andrew Gilbey© 2023 </p>
	</div>
</body>
</html>
