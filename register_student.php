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
    <title>Register as Student</title>
</head>
<body>
    <form action="register_student.php" method="post">
        <label>Full name: </label><br>
        <input type="text" name="name"><br>
        <label>Email: </label><br>
        <input type="mail" name="email"><br> 
        <label>Password: </label><br>
        <input type="password" name="password1"><br>
        <label>Confirm Password: </label><br>
        <input type="password" name="password2"><br>
        <label>Student Number: </label><br>
        <input type="text" name="am"><br>
        <label>Street: </label><br>
        <input type="text" name="street"><br>
        <label>Number: </label><br>
        <input type="text" name="number"><br>
        <label>City: </label><br>
        <input type="text" name="city"><br>
        <label>Postcode: </label><br>
        <input type="text" name="postcode"><br>
        <label>Father's Name: </label><br>
        <input type="text" name="fathersname"><br>
        <label>Cellphone Number: </label><br>
        <input type="text" name="cell"><br>
        <label>Landline-Phone Number: </label><br>
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
        $street = $_POST["street"];
        $number = $_POST["number"];
        $city = $_POST["city"];
        $postcode = $_POST["postcode"];
        $fathersname = $_POST["fathersname"];
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
            echo "{$cell}<br>{$phone}";
            //$_SESSION["email"] = $email;

            register_user($name, $email, $password1, $type, $conn);
            register_student($email, $am, $street, $number, $city, $postcode, $fathersname,  $cell, $phone, $conn);

            //jump_to_site($type);

        }    
    }

?>

<?php
    mysqli_close($conn);
?>
