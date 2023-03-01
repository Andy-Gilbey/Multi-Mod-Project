<?php

    $dbServername = "localhost";
    $dbUsername = "SuperUser";
    $dbPassword = "StillWater2010@";
    $dbName = "Key_Management";

    $key_mg_conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

    // Check the link to the database
	if (!$key_mg_conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>