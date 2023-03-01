<?php

// Include database configuration file
require_once "db.inc.php"; // Sentinel database connection code!
require_once "keymg.inc.php"; // Key Manager database connection code!


//256-bit key size, requires a key length of 32 bytes
$key = random_bytes(32); 

// the data is encrypted using AES-256-CTR cipher and then encoded using base64, which obscures the encrypted data
function encryptData($formData, $key)
{
    //  Encrypt using AES-CTR
    $ivlen = openssl_cipher_iv_length($cipher = "AES-256-CTR");
    $iv = random_bytes($ivlen);
    $ciphertext = openssl_encrypt(
        $formData,
        $cipher,
        $key,
        $options = OPENSSL_RAW_DATA,
        $iv
    );
    return base64_encode($iv . $ciphertext);
}



// This checks if the form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // grab all the data from the form
    $username = $_POST["username"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $dob = $_POST["dob"];
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

// check if username already exists
$query = "SELECT * FROM User WHERE username = ?";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $query)) {
    // handle error
} else {
    // bind parameter and execute statement
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    // store result
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        // username already exists, handle error
        header("Location: error.php?error=usernameexists");
        exit();
    }
}

// Prepared statement to insert user data into database to prevent SQL injection
$query = "INSERT INTO User (username, firstname, lastname, dob, address1, address2, county, eircode, phone, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $query)) {
    // handle error
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

    // Bind the parameters to the SQL statement, the bunch of s in parameter 2 shows that 11 strings are being bound
    // All the encrypted data then is bound into the statement
    mysqli_stmt_bind_param(
        $stmt,
        "ssssssssss",
        $username,
        $encryptedFirstname,
        $encryptedLastname,
        $encryptedDob,
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

    // Gotta Save the key
    //The mysqli_insert_id() function returns the id (generated with AUTO_INCREMENT) from the last query.
    $user_id = mysqli_insert_id($conn);
    // Debug
    echo $user_id;
    $key_query = "INSERT INTO Key_Manager_Data (user_id, key_value) VALUES (?, ?)";
    $stmt = $key_mg_conn->prepare($key_query);
    $hex_key = bin2hex($key);
    $stmt->bind_param("is", $user_id, $hex_key);
    $stmt->execute();
    $key_mg_conn->close();
    $stmt->close();



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