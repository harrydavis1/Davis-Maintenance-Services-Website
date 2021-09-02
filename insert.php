<?php
$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

if (!empty($name) || !empty($email) || !empty($message)){
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbname = "davismaintenance_db";

    //create connection
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);

    if (mysqli_connect_error()) {
        die('Connect Error('. mysqli_connect_errno().')'. mysqli_connect_error());
    }
    else {
        $SELECT = "SELECT email From customer_support Where email = ? Limit 1";
        $INSERT = "INSERT Into customer_support (name, email, message) values(?, ?, ?)";

        //prepare statement
        $stmt = $conn->prepare($SELECT);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($email);
        $stmt->store_result();
        $rnum = $stmt->num_rows;

        if ($rnum==0) {
            $stmt->close();

            $stmt = $conn->prepare($INSERT);
            $stmt->bind_param("sss", $name, $email, $message);
            $stmt->execute();
            echo "Message Sent";
        }
        else {
            echo "Email already in use";
        }
        $stmt->close();
        $conn->close();
    }
}
else {
    echo "All fields are required";
    die();
}
?>