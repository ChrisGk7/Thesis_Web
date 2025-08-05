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
    <title>Secretary</title>
</head>
<body>
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

    echo "Secretary Page<br>";
    //echo $_POST["email"];

    if(check_user_type($_SESSION['email'], $conn) == "secretary"){
        echo "Email is: ";
        echo $_SESSION['email']; // display the message
    }
    else{
        header("Location: index.php");
    }

?>

<?php
    //mysqli_close($conn);
    
    try{
        mysqli_close($conn);
    }
    catch(TypeError){
        echo "";
    }
    
?>