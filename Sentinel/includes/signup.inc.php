<?php


// Include database configuration
require_once('db.inc.php'); // your database connection code


function encryptData($formData, $key)
{
    $ivlen = openssl_cipher_iv_length($cipher = "AES-256-CTR");
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext = openssl_encrypt($formData, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $ciphertext);
}
$key = openssl_random_pseudo_bytes(32); // Define the key 

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $county = $_POST['county'];
    $eircode = $_POST['eircode'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
}

    //Convert date to it's string format in order to encrypt it 
    $dob = DateTime::createFromFormat('Y-m-d', $dob);
    $dobString = $dob->format('Y-m-d');
    // Hash and Salt the Password
    $hashedPwd = password_hash($password,PASSWORD_DEFAULT);

  


    // Prepare SQL statement to insert user data into database
    $query = "INSERT INTO User (username, firstname, lastname, dob, email, address1, address2, county, eircode, phone, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_stmt_init($conn);

     // Check if username already exists
    $query = "SELECT COUNT(*) FROM User WHERE username = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $query)) {
        header("Location: ../signup.php?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);

        if ($count > 0) {
            // Username already exists, handle error
            echo "<script>alert('This username is already taken. Please choose a different one.')</script>";
            echo "<script>window.location.href='../signup.php';</script>";
            exit();
        } else {
            if (!mysqli_stmt_prepare($stmt, $query)) {

            } 
            else 
            {
                // Encrypt personal information
                $encryptedFirstname = encryptData($firstname, $key);
                $encryptedLastname = encryptData($lastname, $key);
                $encryptedEmail = encryptData($email, $key);
                $encryptedAddress1 = encryptData($address1, $key);
                $encryptedAddress2 = encryptData($address2, $key);
                $encryptedCounty = encryptData($county, $key);
                $encryptedEircode = encryptData($eircode, $key);
                $encryptedDob = encryptData($dobString, $key);
                $encryptedPhone = encryptData($phone, $key);
                 mysqli_stmt_bind_param($stmt, "sssssssssss", $username, $encryptedFirstname, $encryptedLastname, $encryptedDob, $encryptedEmail, $encryptedAddress1, $encryptedAddress2, $encryptedCounty, $encryptedEircode, $encryptedPhone, $hashedPwd);
            }
            // Execute prepared statement
            if ($stmt->execute()) {
                // Redirect to success page
                header('Location: success.php');
                exit();
            } else {
                // Redirect to error page
                header('Location: error.php');
                exit();
            }
        }
        mysqli_stmt_close($stmt);
}


?>
