<?php
    include_once "dbconn.php";

    $Name = $_POST["Name"];
    $Email = $_POST["Email"];
    $Message = $_POST["Message"];

    //Inserting User data to SQL Database
        $sql = "INSERT INTO contact(Name, Email, Message)
        VALUES('$Name', '$Email', '$Message')";
        mysqli_query($conn, $sql);
        header("Location: index.php");
?>

