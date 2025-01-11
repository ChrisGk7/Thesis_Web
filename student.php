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
    <title>Student</title>
</head>
<body>
    <form action = "student.php" method="post">
        <input type="submit" name="logout" value="logout">
    </form>
</body>
</html>

<?php
    //session_start();

    /*
    if (isset($_POST["logout"])){
        session_destroy();
        header("Location: index.php");
    }
    */

    echo "Student Page<br>";
    //echo $_POST["email"];

    if(isset($_SESSION['email'])){
        echo $_SESSION['email']; // display the message
        unset($_SESSION['email']); // clear the value so that it doesn't display again
    }
    else{
        header("Location: index.php");
    }

?>

<?php
    mysqli_close($conn);
?>