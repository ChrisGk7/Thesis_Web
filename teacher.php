<?php

    session_start();
    include("header.html");
    include("database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher</title>
</head>
<body>
</body>
</html>

<?php
    //session_start();

    echo "Teacher Page<br>";
    //echo $_POST["email"];
    
    /*
    if (isset($_POST["logout"])){
        session_destroy();
        header("Location: index.php");
    }
    */

    if(check_user_type($_SESSION['email'], $conn) == "teacher"){
        echo $_SESSION['email']; // display the message
        $email = $_SESSION['email'];
        $_SESSION['email'] = $email;
    }
    else{
        header("Location: index.php");
    }
?>

<?php
    mysqli_close($conn);
?>
