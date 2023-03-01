<style>
@import url('https://fonts.googleapis.com/css2?family=Golos+Text:wght@500&family=Roboto:wght@100&display=swap');
</style> 

<?php
session_start();
$user_ID = $_SESSION['user_ID'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Upload Your Close Contacts</title>
	<link rel="stylesheet" type="text/css" href="style\style.css">
</head>
<body>
<header>
  <h1>Welcome to Sentinel</h1>

  <nav class="navbar">
            <a href="home.php">Home</a>
            <a href="upload.php">Upload an Antigen</a>
            <a href="upload.php">Manage Uploads</a>
            <a href="includes/logout.inc.php">Logout</a>
          </nav>
</header>


<div class="wrapper">
        <div class="form">
            <h1><img src="images/sent-logo.png" alt="Sentinel" width="200"></h1>
            <b><p> Welcome to the Sentinel Portal</p></b>

            <form action="includes/ccUpload.inc.php" method="post">
            <label for="firstname">First Name:</label>
            <input type="text" id="CC_Firstname" name="CC_Firstname">

            <label for="lastname">Last Name:</label>
            <input type="text" id="CC_Lastname" name="CC_Lastname">

            <label for="phone">Phone Number:</label>
            <input type="text" id="CC_Phone" name="CC_Phone">
            <button type="submit" name="submit">Submit</button>
        </form>
        <br>

        <h2>Current Close Contacts</h2>
        <table>
  <tr>

    <th>First Name &nbsp</th>
    <th>Last Name &nbsp</th>
    <th>Phone Number &nbsp</th>

  </tr>
  <?php

include_once "includes/db.inc.php"; //include the database connection script
include_once "includes/decrypt.php";
require_once "includes/keymg.inc.php"; 

$user_ID = $_SESSION['user_ID'];
$userID = $user_ID; // Define $userID variable

// Fetch all keys for the current user
$key_query = "SELECT cc_key FROM Key_Manager_CloseContacts WHERE user_id = ?";
$stmt = $key_mg_conn->prepare($key_query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

// Store the keys in an array
$keys = array();
while ($row = mysqli_fetch_assoc($result)) {
    $keys[] = hex2bin($row['cc_key']);
}

$stmt->close();

// Get the close contacts for the current user
$sql = "SELECT CC_Firstname, CC_LastName, CC_Phone FROM Close_Contacts WHERE FK_userID = $userID";
$result = mysqli_query($conn, $sql);

// Loop through the close contacts and generate a row for each one
$i = 0;
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . decryptData($row['CC_Firstname'], $keys[$i]) . "</td>";
    echo "<td>" . decryptData($row['CC_LastName'], $keys[$i]) . "</td>";
    echo "<td>" . decryptData($row['CC_Phone'], $keys[$i]) . "</td>";
    echo "</tr>";
    $i++;
}
?>
</table>
<br>
<div class="smalltext">
    <I> If any close contact information is incorrect please contact administrator@sentinel.ie as soon as possible </i>
</div>
    </div>
</div>

<footer>
    <div class="footer">
        <p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sentinel <br> Andrew Gilbey© 2023 </p>
    </div>
</footer>

</body>
</html>
