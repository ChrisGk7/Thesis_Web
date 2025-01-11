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
    <title>Register</title>
</head>
<body>
    <form action="register_student.php" method="post">
        <label>full name: </label><br>
        <input type="text" name="name"><br>
        <label>email: </label><br>
        <input type="text" name="email"><br>
        <label>password: </label><br>
        <input type="password" name="password1"><br>
        <label>confirm password: </label><br>
        <input type="password" name="password2"><br>
        <label>AM: </label><br>
        <input type="text" name="am"><br>
        <label>Cellphone: </label><br>
        <input type="text" name="cell"><br>
        <label>Localphone: </label><br>
        <input type="text" name="phone"><br>
        <input type="submit" name="login" value="Register">
    </form>
</body>
</html>

<?php


    $emails = array("1", "2", "3");

    if (isset($_POST["login"])){
        $email = $_POST["email"];
        $name = $_POST["name"];
        $type = "student";
        $password1 = $_POST["password1"];
        $password2 = $_POST["password2"];
        $am = $_POST["am"];
        $cell = $_POST["cell"];
        $phone = $_POST["phone"];

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
            
            $_SESSION["email"] = $email;

            register_user($name, $email, $password1, $type, $conn);
            register_student($email, $am, $cell, $phone, $conn);

            //jump_to_site($type);

        }    
    }

?>

<?php
    mysqli_close($conn);
?>