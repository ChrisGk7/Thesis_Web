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
    <title>Register as Teacher</title>
</head>
<body>
    <form action="register_teacher.php" method="post">
        <label>Full Name: </label><br>
        <input type="text" name="name" placeholder="John Doe"required><br>
        <label>Email: </label><br>
        <input type="email" name="email" placeholder="Teacher@upnet.gr"required><br>
        <label>Password: </label><br>
        <input type="password" name="password1"required><br>
        <label>Confirm Password: </label><br>
        <input type="password" name="password2"required><br>
        <label>Topic: </label><br>
        <input type="text" name="topic" placeholder="Topic"required><br>
        <label>Landline: </label><br>
        <input type="text" name="landline" placeholder="226XXXXXXX"required><br>
        <label>Mobile: </label><br>
        <input type="text" name="mobile" placeholder="693XXXXXXX"required><br>
        <label>Department: </label><br>
        <input type="text" name="department" placeholder="CEID"required><br>
        <label>University: </label><br>
        <input type="text" name="university" placeholder="Patras"required><br>
        <input type="submit" name="login" value="Register">
    </form>
</body>
</html>

<?php


    //$emails = array("1", "2", "3");

    if (isset($_POST["login"])){

        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
        $type = "teacher";
        $password1 = filter_input(INPUT_POST, "password1", FILTER_SANITIZE_SPECIAL_CHARS);
        $password2 = filter_input(INPUT_POST, "password2", FILTER_SANITIZE_SPECIAL_CHARS);
        $topic = filter_input(INPUT_POST, "topic", FILTER_SANITIZE_SPECIAL_CHARS);
        $landline = filter_input(INPUT_POST, "landline", FILTER_SANITIZE_SPECIAL_CHARS);
        $mobile = filter_input(INPUT_POST, "mobile", FILTER_SANITIZE_SPECIAL_CHARS);
        $department = filter_input(INPUT_POST, "department", FILTER_SANITIZE_SPECIAL_CHARS);
        $university = filter_input(INPUT_POST, "university", FILTER_SANITIZE_SPECIAL_CHARS);

        /*
        $name = $_POST["name"];
        $type = "teacher";
        $password1 = $_POST["password1"];
        $password2 = $_POST["password2"];
        $topic = $_POST["topic"];
        $landline = $_POST["landline"];
        $mobile = $_POST["mobile"];
        $department = $_POST["department"];
        $university = $_POST["university"];
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
                register_teacher($email, $topic, $landline, $mobile, $department, $university, $conn);
            }
            
            catch(mysqli_sql_exception){
                echo "User with email {$email} Already Exists";
            }

            /*
            register_user($name, $email, $password1, $type, $conn);
            register_teacher($email, $topic, $landline, $mobile, $department, $university, $conn);
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