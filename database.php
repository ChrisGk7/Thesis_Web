<?php

    $db_server = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "project"; // change this
    $conn;

    try{
        $conn = mysqli_connect($db_server, 
                               $db_user, 
                               $db_password, 
                               $db_name);

    }
    catch(mysqli_sql_exception){
        echo "Could not connect to the Database";
    }


    // returns true if the email is registered in the database
    function check_user_in_db($email, $conn){
        
        $sql = "SELECT email FROM users WHERE user = '$email'";

        $result = mysqli_query($conn, $sql);
        if (empty($result)){
            return false;
        }
        else{
            return true;
        }
    }

    // returns the type of user (Student, Teacher, Secretary)
    // user/email MUST be in database
    // use check_user_in_db first
    function check_user_type($email, $conn){
        
        $sql = "SELECT email FROM users WHERE user = '$email'";
        
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row["type"];
    }

    function register_user($email, $password, $conn){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users VALUES ('$email', '$hash')";
        mysqli_query($conn, $sql);
    }

    function jump_to_site($type){
        // fix with enums
        if ($type == "1"){
            header("Location: student.php");
        }
        elseif ($type == "2"){
            header("Location: teacher.php");
        }
        elseif ($type == "3"){
            header("Location: secretary.php");
        }
        else{
            // This should never occure
            echo "Unexpected User Type";
        }
    }


?>