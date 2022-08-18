<?php
    include_once "dbconn.php";

    $Name = $_POST["Name"];
    $Email = $_POST["Email"];
    $Message = $_POST["Message"];

    //Making Connection
        $sql = "INSERT INTO contact(Name, Email, Message)
        VALUES('$Name', '$Email', '$Message')";
        mysqli_query($conn, $sql);
        header("Location: home.html");
?>

