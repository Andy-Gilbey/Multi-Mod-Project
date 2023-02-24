<?php
require_once('db.inc.php');

if (isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM User WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['Password'])) {
            session_start();
            $_SESSION['username'] = $row['username'];
            header('Location: ../home.php');
            exit();
        } else {
            echo "Incorrect password";
        }
    } else {
        echo "User not found";
    }
    $stmt->close(); //Statement close
    $conn->close(); //Connection close
}
?>
