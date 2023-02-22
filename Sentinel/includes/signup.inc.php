<?php
// Include database configuration
require_once 'db.inc.php';

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

    // Prepare SQL statement to insert user data into database
    $sql = "INSERT INTO User (username, firstname, lastname, dob, EmailAddress, address1, address2, county, eircode, phone, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Create prepared statement
    $stmt = $conn->prepare($sql);

    // Bind parameters to prepared statement with a whole rake of strings included
    $stmt->bind_param("sssssssssss", $username, $firstname, $lastname, $dob, $email, $address1, $address2, $county, $eircode, $phone, $password);

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

?>
