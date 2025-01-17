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
    <title>Register as Secretary</title>
</head>
<body>
    <form action="register_secretary.php" method="post">
        <label>Full name: </label><br>
        <input type="text" name="name" placeholder="John Doe"required><br>
        <label>Email: </label><br>
        <input type="email" name="email" placeholder="Secretary@upnet.gr"required><br>
        <label>Password: </label><br>
        <input type="password" name="password1"required><br>
        <label>Confirm Password: </label><br>
        <input type="password" name="password2"required><br>
        <input type="submit" name="login" value="Register">
    </form>
</body>
</html>

<?php


    //$emails = array("1", "2", "3");

    if (isset($_POST["login"])){

        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
        $type = "secretary";
        $password1 = filter_input(INPUT_POST, "password1", FILTER_SANITIZE_SPECIAL_CHARS);
        $password2 = filter_input(INPUT_POST, "password2", FILTER_SANITIZE_SPECIAL_CHARS);

        /*
        $email = $_POST["email"];
        $name = $_POST["name"];
        $type = "secretary";
        $password1 = $_POST["password1"];
        $password2 = $_POST["password2"];
        */

        if (empty($email)){
            echo "Please Enter Email";
        }
        elseif (empty($password1)){
            echo "Please enter your password";
        }
        elseif (empty($password2)){
            echo "Please enter your password again";
        }
        elseif ($password1 != $password2){
            echo "Passwords don't match";
        }

        else{
            // FOR TESTING PURPOSES ONLY DELETE LATER
            echo "Email is: {$email} and your Password is: {$password1}<br>";
            
            //$_SESSION["email"] = $email;

            try{
                register_user($name, $email, $password1, $type, $conn);
                register_secretary($email, $conn);
            }
            
            catch(mysqli_sql_exception){
                echo "User with email {$email} Already Exists";
            }

            /*
            register_user($name, $email, $password1, $type, $conn);
            register_secretary($email, $conn);
            */
            //jump_to_site($type);

        }    
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