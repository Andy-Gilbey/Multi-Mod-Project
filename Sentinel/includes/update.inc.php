<?php
session_start();
// Include database configuration file
require_once "db.inc.php"; // Sentinel database connection code!
require_once "keymg.inc.php"; // Key Manager database connection code!
include_once "cryptography.inc.php"; // Need for encryption/decryption of data

//Grab the user id from the session
$user_ID = $_SESSION["user_ID"];
$userID = $user_ID; // Define $userID variable

// Extract the key from the database
$key_query = "SELECT key_value FROM Key_Manager_Data WHERE user_id = ?";
$stmt = $key_mg_conn->prepare($key_query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

// Extract the key
$key = "";
if ($row = mysqli_fetch_assoc($result)) {
    $key = $row["key_value"];
}

// This checks if the form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // grab all the data from the form
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $dob = $_POST["dob"];
    $address1 = $_POST["address1"];
    $address2 = $_POST["address2"];
    $county = $_POST["county"];
    $eircode = $_POST["eircode"];
    $phone = $_POST["phone"];
}

$dob = DateTime::createFromFormat("Y-m-d", $dob);
$dobString = $dob->format("Y-m-d");

// Prepared statement to insert user data into database to prevent SQL injection
//Convert date to it's string format in order to encrypt it
$query =
    "UPDATE User SET firstname = ?, lastname = ?, dob = ?, address1 = ?, address2 = ?, county = ?, eircode = ?, phone = ? WHERE user_ID = ?";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $query)) {
    echo "SQL Error";
} else {
    // Encrypt all personal information with the key
    $encryptedFirstname = encryptData($firstname, $key);
    $encryptedLastname = encryptData($lastname, $key);
    $encryptedAddress1 = encryptData($address1, $key);
    $encryptedAddress2 = encryptData($address2, $key);
    $encryptedCounty = encryptData($county, $key);
    $encryptedEircode = encryptData($eircode, $key);
    $encryptedDob = encryptData($dobString, $key);
    $encryptedPhone = encryptData($phone, $key);

    // Bind the parameters to the SQL statement, the bunch of s in parameter 2 shows that 10 strings are being bound
    // All the encrypted data then is bound into the statement, followed by the username parameter
    mysqli_stmt_bind_param(
        $stmt,
        "ssssssssi", // note the "i" at the end for integer parameter
        $encryptedFirstname,
        $encryptedLastname,
        $encryptedDob,
        $encryptedAddress1,
        $encryptedAddress2,
        $encryptedCounty,
        $encryptedEircode,
        $encryptedPhone,
        $user_ID // <- The User ID for the where clause is last to be bound
    );
}

// Execute the prepared statement (inside an if statement to create a condition if it fails to execute)
if (mysqli_stmt_execute($stmt)) {
    // If it works, a redirect to the success page
    header("Location: ../home.php");
    exit();
} else {
    // If it fails...redirect to the error page
    header("Location: ../other/error.php?=sqlerror");
    exit();
}

mysqli_stmt_close($stmt); //close the statement
?>
