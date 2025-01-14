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
        <label>Full Name: </label><br>
        <input type="text" name="name" placeholder="John Doe"><br>
        <label>Email: </label><br>
        <input type="text" name="email" placeholder="up110XXXX@upnet.gr"><br>
        <label>Password: </label><br>
        <input type="password" name="password1"><br>
        <label>Confirm Password: </label><br>
        <input type="password" name="password2"><br>
        <label>AM: </label><br>
        <input type="text" name="am" placeholder="110XXXX"><br>
        <label>Street: </label><br>
        <input type="text" name="street" placeholder="Based Street"><br>
        <label>Street Number: </label><br>
        <input type="text" name="snumber" placeholder="69"><br>
        <label>City: </label><br>
        <input type="text" name="city" placeholder="Patras"><br>
        <label>postcode: </label><br>
        <input type="text" name="postcode" placeholder="26XXX"><br>
        <label>Father Name: </label><br>
        <input type="text" name="fname" placeholder="Joe Doe"><br>
        <label>Cellphone: </label><br>
        <input type="text" name="cell" placeholder="694XXXXXXX"><br>
        <label>Localphone: </label><br>
        <input type="text" name="phone" placeholder="210XXXXXXX"><br>
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
        $snumber = $_POST["snumber"];
        $city = $_POST["city"];
        $postcode = $_POST["am"];
        $father_name = $_POST["fname"];
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
            
            //$_SESSION["email"] = $email;

            register_user($name, $email, $password1, $type, $conn);
            register_student($email, $am, $street, $snumber, $city, $postcode, $father_name, $cell, $phone, $conn);

            //jump_to_site($type);

        }    
    }

?>

<?php
    mysqli_close($conn);
?>