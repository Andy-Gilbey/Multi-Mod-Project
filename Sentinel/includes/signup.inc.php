<?php

// Include database configuration file
require_once "db.inc.php"; // Sentinel database connection code!

// the data is encrypted using AES-256-CTR cipher and then encoded using base64, which obscures the encrypted data
function encryptData($formData, $key)
{
    $ivlen = openssl_cipher_iv_length($cipher = "AES-256-CTR");
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext = openssl_encrypt(
        $formData,
        $cipher,
        $key,
        $options = OPENSSL_RAW_DATA,
        $iv
    );
    return base64_encode($iv . $ciphertext);
}

//256-bit key size, requires a key length of 32 bytes
$key = openssl_random_pseudo_bytes(32); // Define the key

// This checks if the form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // grab all the data from the form
    $username = $_POST["username"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $dob = $_POST["dob"];
    $email = $_POST["email"];
    $address1 = $_POST["address1"];
    $address2 = $_POST["address2"];
    $county = $_POST["county"];
    $eircode = $_POST["eircode"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
}

//Convert date to it's string format in order to encrypt it
$dob = DateTime::createFromFormat("Y-m-d", $dob);
$dobString = $dob->format("Y-m-d");
// Hash and Salt the Password using the PASSWORD_DEFAULT algorithim
// Adding your own salt is depreciated, this process is done automatically.
$hashedPwd = password_hash($password, PASSWORD_DEFAULT);

// Prepared statement to insert user data into database to prevent SQL injection
$query =
    "INSERT INTO User (username, firstname, lastname, dob, email, address1, address2, county, eircode, phone, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $query)) {
    // handle error
} else {
    // bind parameters and execute statement

    // Encrypt all personal information with the key
    $encryptedFirstname = encryptData($firstname, $key);
    $encryptedLastname = encryptData($lastname, $key);
    $encryptedEmail = encryptData($email, $key);
    $encryptedAddress1 = encryptData($address1, $key);
    $encryptedAddress2 = encryptData($address2, $key);
    $encryptedCounty = encryptData($county, $key);
    $encryptedEircode = encryptData($eircode, $key);
    $encryptedDob = encryptData($dobString, $key);
    $encryptedPhone = encryptData($phone, $key);

    // Bind the parameters to the SQL statement, the bunch of s in parameter 2 shows that 11 strings are being bound
    // All the encrypted data then is bound into the statement
    mysqli_stmt_bind_param(
        $stmt,
        "sssssssssss",
        $username,
        $encryptedFirstname,
        $encryptedLastname,
        $encryptedDob,
        $encryptedEmail,
        $encryptedAddress1,
        $encryptedAddress2,
        $encryptedCounty,
        $encryptedEircode,
        $encryptedPhone,
        $hashedPwd
    );
}
// Execute the prepared statement
if ($stmt->execute()) {
    // If it works, a redirect to the success page
    header("Location: success.php");
    exit();
} else {
    // If it fails, redirect to the error page
    // NOT YET IMPLEMENTED
    header("Location: error.php");
    exit();
}
mysqli_stmt_close($stmt); //close the statement
?>
