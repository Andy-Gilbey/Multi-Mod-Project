<?php

session_start();
include_once "db.inc.php";
require_once "keymg.inc.php"; 


if (isset($_POST["submit"])) {
    $userID = $_SESSION["user_ID"]; //Get he user ID from the authenticated session
    $image = file_get_contents($_FILES["antigen"]["tmp_name"]); //retrieves the temporary name of an uploaded image file from the $_FILE
    $date = date("Y-m-d"); // formats date the as a string in the Y-m-d
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




    // Insert encrypted data into database
    $sql =  "INSERT INTO Antigens (FK_UserID, AG_Image, DateOfTest) VALUES (?, ?, ?)";

    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "SQL Error";
    } else {

        //Encrypt the fields required
        $encrypted_image = encryptData($image, $key);
        $encrypted_date = encryptData($date, $key);
        // Bind to the statement
        mysqli_stmt_bind_param(
            $stmt,
            "iss",
            $userID,
            $encrypted_image,
            $encrypted_date
        );

        // Execute the prepared statement
        if ($stmt->execute()) { // If it goes through

            $user_id = mysqli_insert_id($conn);

            // Debug
            echo $user_id;
        
            $key_query = "INSERT INTO Key_Manager_Antigen (user_id, antigen_key) VALUES (?, ?)";
            $stmt = $key_mg_conn->prepare($key_query);
            $hex_key = bin2hex($key);
            $stmt->bind_param("is", $user_id, $hex_key);
            $stmt->execute();
            $key_mg_conn->close();
            $stmt->close();

        header("Location: ../successUpload.php");
        exit();
        }else { // if it fails
                echo "Failed";
                exit();
            }
        }
            mysqli_stmt_close($stmt); //close the statement

            //Save the Key
                //The mysqli_insert_id() function returns the id (generated with AUTO_INCREMENT) from the last query.


    ?>
