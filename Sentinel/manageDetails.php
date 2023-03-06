<!--Google Font Import-->
<style>
@import url('https://fonts.googleapis.com/css2?family=Golos+Text:wght@500&family=Roboto:wght@100&display=swap');
</style> 

<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Details Management</title>
	<link rel="stylesheet" type="text/css" href="style\style.css">
</head>
<body>
	<header>
        <h1> Manage your details</h1>
        <nav class="navbar">
           <a href="home.php">Home</a>
            <a href="upload.php">Upload an Antigen</a>
            <a href="closeContacts.php">Manage Close Contacts</a>
            <a href="includes/logout.inc.php">Logout</a>
          </nav>
	</header>


  <!-- Pull Data from Database  -->
  <?php
  include_once "includes/db.inc.php"; //include the database connection script
  include_once "includes/cryptography.inc.php";
  require_once "includes/keymg.inc.php";

  $user_ID = $_SESSION["user_ID"];
  $userID = $user_ID; // Define $userID variable

  // Extract the key from the database
  $key_query = "SELECT key_value FROM Key_Manager_Data WHERE user_id = ?";
  $stmt = $key_mg_conn->prepare($key_query);
  $stmt->bind_param("i", $userID);
  $stmt->execute();
  $result = $stmt->get_result();

  // Store the key
  $key = "";
  if ($row = mysqli_fetch_assoc($result)) {
      $key = $row["key_value"];
  }

  // Retrieve data from the database
  $sql ="SELECT FirstName,LastName,Address1,Address2,County,Eircode,Dob,Phone FROM User Where user_ID = ?";
  $stmt = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($stmt, $sql)) {
      echo "oops";
      exit();
  }
  mysqli_stmt_bind_param($stmt, "i", $userID);
  mysqli_stmt_execute($stmt);

  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      // Output data of each row
      // Assign each to a variable so we can populate the form
      // Each Variable is the encrypted data though...
      while ($row = $result->fetch_assoc()) {
          $firstname = $row["FirstName"];
          $lastname = $row["LastName"];
          $dob = $row["Dob"];
          $address1 = $row["Address1"];
          $address2 = $row["Address2"];
          $county = $row["County"];
          $eircode = $row["Eircode"];
          $phone = $row["Phone"];

          // Use the decryptData function from crytpo file to decrypt all the data
          $decryptedFirstname = decryptData($firstname, $key);
          $decryptedLastname = decryptData($lastname, $key);
          $decryptedDob = decryptData($dob, $key);
          $decryptedAddress1 = decryptData($address1, $key);
          $decryptedAddress2 = decryptData($address2, $key);
          $decryptedCounty = decryptData($county, $key);
          $decryptedEircode = decryptData($eircode, $key);
          $decryptedPhone = decryptData($phone, $key);

          // Gotta format the date to ensure it displays correctly.
          $formattedDob = date("Y-m-d", strtotime($decryptedDob));

      }
  } else {
      echo "Something obviously went wrong here...query picked up zero results.";
  }
  ?>
  <!-- End the extraction -->


	<div class="wrapper">
        <div class="form">
        <img src="images/sent-logo.png" alt="Sentinel" width="200">
			<h2>Your Details</h2>

			<form method="post" action="includes/update.inc.php">

      <label for="userID">ID:</label>
				<input type="text" id="userID" name="userID"  value="<?php echo $userID; ?>" disabled>
				<label for="firstname">First Name:</label>
				<input type="text" id="firstname" name="firstname" placeholder="Enter your first name" maxlength="50" value="<?php echo $decryptedFirstname; ?>" required>
				<label for="lastname">Last Name:</label>
				<input type="text" id="lastname" name="lastname" placeholder="Enter your last name" maxlength="50" value="<?php echo $decryptedLastname; ?>" required>
        <label for="dob">Date of Birth:</label>
				<input type="date" id="dob" name="dob" value="<?php echo $formattedDob; ?>" required>
				<label for="address">Address Line 1:</label>
				<input type="text" id="address1" name="address1" placeholder="Enter your House Number" maxlength="50" value="<?php echo $decryptedAddress1; ?>" required>
                <label for="address">Address Line 2:</label>
				<input type="text" id="address2" name="address2" placeholder="Enter your Street Address" maxlength="50" value="<?php echo $decryptedAddress2; ?>" required>

        <label for="county">County:</label>
<select name="county" id="county">
    <option value="Carlow" <?php if ($decryptedCounty == "Carlow") {
        echo "selected";
    } ?>>Carlow</option>
    <option value="Cavan" <?php if ($decryptedCounty == "Cavan") {
        echo "selected";
    } ?>>Cavan</option>
    <option value="Clare" <?php if ($decryptedCounty == "Clare") {
        echo "selected";
    } ?>>Clare</option>
    <option value="Cork" <?php if ($decryptedCounty == "Cork") {
        echo "selected";
    } ?>>Cork</option>
    <option value="Donegal" <?php if ($decryptedCounty == "Donegal") {
        echo "selected";
    } ?>>Donegal</option>
    <option value="Dublin" <?php if ($decryptedCounty == "Dublin") {
        echo "selected";
    } ?>>Dublin</option>
    <option value="Galway" <?php if ($decryptedCounty == "Galway") {
        echo "selected";
    } ?>>Galway</option>
    <option value="Kerry" <?php if ($decryptedCounty == "Kerry") {
        echo "selected";
    } ?>>Kerry</option>
    <option value="Kildare" <?php if ($decryptedCounty == "Kildare") {
        echo "selected";
    } ?>>Kildare</option>
    <option value="Kilkenny" <?php if ($decryptedCounty == "Kilkenny") {
        echo "selected";
    } ?>>Kilkenny</option>
    <option value="Laois" <?php if ($decryptedCounty == "Laois") {
        echo "selected";
    } ?>>Laois</option>
    <option value="Leitrim" <?php if ($decryptedCounty == "Leitrim") {
        echo "selected";
    } ?>>Leitrim</option>
    <option value="Limerick" <?php if ($decryptedCounty == "Limerick") {
        echo "selected";
    } ?>>Limerick</option>
    <option value="Longford" <?php if ($decryptedCounty == "Longford") {
        echo "selected";
    } ?>>Longford</option>
    <option value="Louth" <?php if ($decryptedCounty == "Louth") {
        echo "selected";
    } ?>>Louth</option>
    <option value="Mayo" <?php if ($decryptedCounty == "Mayo") {
        echo "selected";
    } ?>>Mayo</option>
    <option value="Meath" <?php if ($decryptedCounty == "Meath") {
        echo "selected";
    } ?>>Meath</option>
    <option value="Monaghan" <?php if ($decryptedCounty == "Monaghan") {
        echo "selected";
    } ?>>Monaghan</option>
    <option value="Offaly" <?php if ($decryptedCounty == "Offaly") {
        echo "selected";
    } ?>>Offaly</option>
    <option value="Roscommon" <?php if ($decryptedCounty == "Roscommon") {
        echo "selected";
    } ?>>Roscommon</option>
    <option value="Sligo" <?php if ($decryptedCounty == "Sligo") {
        echo "selected";
    } ?>>Sligo</option>
    <option value="Tipperary" <?php if ($decryptedCounty == "Tipperary") {
        echo "selected";
    } ?>>Tipperary</option>
    <option value="Waterford" <?php if ($decryptedCounty == "Waterford") {
        echo "selected";
    } ?>>Waterford</option>
    <option value="Westmeath" <?php if ($decryptedCounty == "Westmeath") {
        echo "selected";
    } ?>>Westmeath</option>
    <option value="Wexford" <?php if ($decryptedCounty == "Wexford") {
        echo "selected";
    } ?>>Wexford</option>
    <option value="Wicklow" <?php if ($decryptedCounty == "Wicklow") {
        echo "selected";
    } ?>>Wicklow</option>
                </select>
                <label for="address">Eircode:</label>
				<input type="text" id="eircode" name="eircode" placeholder="Enter Eircode" maxlength="9"  value="<?php echo $decryptedEircode; ?>" required>
                <label for="phone">Phone:</label>
				<input type="text" id="phone" name="phone" placeholder="Enter Phone Number" maxlength="20" value="<?php echo $decryptedPhone; ?>" required>
				<div class="button-container">
					<button type="submit" id="submit-button" >Update</button>
                    <a href="home.php">	<button type="button">Quit</button></a>
				</div>
			</form>
    </div>
    </div>
	<footer>
		<div class="footer">
            <p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sentinel <br> Andrew Gilbey© 2023 </p>
        </div>
	</footer>

</body>
</html>
