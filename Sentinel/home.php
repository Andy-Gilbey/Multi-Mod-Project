<!--Google Font Import-->
<style>
@import url('https://fonts.googleapis.com/css2?family=Golos+Text:wght@500&family=Roboto:wght@100&display=swap');
</style> 

<?php
session_start(); //creates a session

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Welcome to Sentinel</title>
	<link rel="stylesheet" type="text/css" href="style\style.css">
</head>
<body>
	<header>

        <h1> Welcome to Sentinel</h1>

        <nav class="navbar">
            <a href="upload.php">Upload an Antigen</a>
            <a href="closeContacts.php">Manage Close Contacts</a>
            <a href="manageDetails.php">Manage Details</a>
            <a href="includes/logout.inc.php">Logout</a>
          </nav>

          
	</header>
	<div class="wrapper">
        <div class="form">
        <h1><img src="images/sent-logo.png" alt="Sentinel" width="200"></h1>
        <b><p> Welcome to the Senitnel Portal</p></b>	
        <?php
            echo "User ID: " . $_SESSION['user_ID'];
        ?>

	

    </div>
    </div>
	<footer>
		<div class="footer">
            <p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sentinel <br> Andrew Gilbey© 2023 </p>
        </div>
	</footer>

</body>
</html>
