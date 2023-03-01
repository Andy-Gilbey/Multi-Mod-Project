<?php

session_start();
include_once "db.inc.php";
require_once "keymg.inc.php";

if (isset($_POST["submit"])) {
    $userID = $_SESSION["user_ID"]; //Get he user ID from the authenticated session
    $firstname = $_POST["CC_Firstname"];
    $lastname = $_POST["CC_Lastname"];
    $phone = $_POST["CC_Phone"];
}
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

// Prepare the Prepared sTATEMENT
$sql =
    "INSERT INTO Close_Contacts (FK_userID, CC_Firstname, CC_Lastname, CC_Phone) VALUES (?, ?, ?, ?)";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "SQL Error";
} else {
    // Encrypt the form data
    $encrypted_firstname = encryptData($firstname, $key);
    $encrypted_lastname = encryptData($lastname, $key);
    $encrypted_phone = encryptData($phone, $key);

    // Bind to the statement
    mysqli_stmt_bind_param(
        $stmt,
        "isss",
        $userID,
        $encrypted_firstname,
        $encrypted_lastname,
        $encrypted_phone
    );
}
if ($stmt->execute()) {
    // If it goes through

    // Debug
    echo $user_id;

    $key_query =
        "INSERT INTO Key_Manager_CloseContacts (user_id, cc_key) VALUES (?, ?)";
    $stmt = $key_mg_conn->prepare($key_query);
    $hex_key = bin2hex($key);
    $stmt->bind_param("is", $userID, $hex_key);
    $stmt->execute();
    $key_mg_conn->close();
    $stmt->close();

    header("Location: includes/successCC.php");
    exit();
} else {
    // if it fails
    echo "Failed for reasons...";
    exit();
}
mysqli_stmt_close($stmt); //close the statement

//Save the Key
//The mysqli_insert_id() function returns the id (generated with AUTO_INCREMENT) from the last query.

?>
