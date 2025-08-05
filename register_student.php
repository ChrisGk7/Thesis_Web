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
        <input type="text" name="name" placeholder="John Doe"required><br>
        <label>Email: </label><br>
        <input type="email" name="email" placeholder="up110XXXX@upnet.gr"required><br>
        <label>Password: </label><br>
        <input type="password" name="password1"required><br>
        <label>Confirm Password: </label><br>
        <input type="password" name="password2"required><br>
        <label>AM: </label><br>
        <input type="text" name="am" placeholder="110XXXX"required><br>
        <label>Street: </label><br>
        <input type="text" name="street" placeholder="Based Street"required><br>
        <label>Street Number: </label><br>
        <input type="text" name="snumber" placeholder="69"required><br>
        <label>City: </label><br>
        <input type="text" name="city" placeholder="Patras"required><br>
        <label>postcode: </label><br>
        <input type="text" name="postcode" placeholder="26XXX"required><br>
        <label>Father Name: </label><br>
        <input type="text" name="fname" placeholder="Joe Doe"required><br>
        <label>Cellphone: </label><br>
        <input type="text" name="cell" placeholder="694XXXXXXX"required><br>
        <label>Localphone: </label><br>
        <input type="text" name="phone" placeholder="210XXXXXXX"required><br>
        <input type="submit" name="login" value="Register">
    </form>
</body>
</html>

<?php


    $emails = array("1", "2", "3");

    if (isset($_POST["login"])){


        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
        $type = "student";
        $password1 = filter_input(INPUT_POST, "password1", FILTER_SANITIZE_SPECIAL_CHARS);
        $password2 = filter_input(INPUT_POST, "password2", FILTER_SANITIZE_SPECIAL_CHARS);
        $am = filter_input(INPUT_POST, "am", FILTER_SANITIZE_SPECIAL_CHARS);
        $street = filter_input(INPUT_POST, "street", FILTER_SANITIZE_SPECIAL_CHARS);
        $snumber = filter_input(INPUT_POST, "snumber", FILTER_SANITIZE_SPECIAL_CHARS);
        $city = filter_input(INPUT_POST, "city", FILTER_SANITIZE_SPECIAL_CHARS);
        $postcode = filter_input(INPUT_POST, "postcode", FILTER_SANITIZE_SPECIAL_CHARS);
        $father_name = filter_input(INPUT_POST, "fname", FILTER_SANITIZE_SPECIAL_CHARS);
        $cell = filter_input(INPUT_POST, "cell", FILTER_SANITIZE_SPECIAL_CHARS);
        $phone = filter_input(INPUT_POST, "phone", FILTER_SANITIZE_SPECIAL_CHARS);

        /*
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
                try{
                    register_student($email, $am, $street, $snumber, $city, $postcode, $father_name, $cell, $phone, $conn);
                }
                catch(mysqli_sql_exception){
                    echo "User with AM {$am} already exists";
                    delete_table_row("user", "email", $email, $conn);
                }
            }
            
            catch(mysqli_sql_exception){
                echo "User with email {$email} already exists";
            }

            /*
            register_user($name, $email, $password1, $type, $conn);
            register_student($email, $am, $street, $snumber, $city, $postcode, $father_name, $cell, $phone, $conn);
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