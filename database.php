<?php

    $db_server = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "thesis_management"; // change this
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
        
        $sql = "SELECT * FROM user WHERE email = '$email'";
        //empty($result)
        $result = mysqli_query($conn, $sql);
        
        return mysqli_num_rows($result)>0;
    
    }

    // returns the type of user (Student, Teacher, Secretary)
    // user/email MUST be in database
    // use check_user_in_db first
    function check_user_type($email, $conn){
        
        $sql = "SELECT * FROM user WHERE email = '$email'";
        
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        //$test = $row["type"];
       // echo "{$test}";
        return $row["type"];
    }

    // register logic

    function register_user($name, $email, $password, $type, $conn){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user VALUES ('$email', '$hash', '$name', '$type', DEFAULT)";
        mysqli_query($conn, $sql);
    }
    
    function register_student($email, $am, $street, $number, $city, $postcode, $father_name, $cell, $phone, $conn){
        $sql = "INSERT INTO student VALUES('$email', '$am', '$street', '$number', '$city', '$postcode', '$father_name',  '$cell', '$phone')";
        mysqli_query($conn, $sql);
    }

    function register_teacher($email, $topic, $landline, $mobile, $department, $university, $conn){
        $sql = "INSERT INTO teacher VALUES('$email', '$topic', '$landline', '$mobile', '$department', '$university')";
        mysqli_query($conn, $sql);
    }

    function register_secretary($email, $conn){
        $sql = "INSERT INTO secretary VALUES('$email')";
        mysqli_query($conn, $sql);
    }

    // login logic

    function jump_to_site($type){
        // fix with enums
        if ($type == "student"){
            header("Location: student.php");
        }
        elseif ($type == "teacher"){
            header("Location: teacher.php");
        }
        elseif ($type == "secretary"){
            header("Location: secretary.php");
        }
        else{
            // This should never occure
            echo "Unexpected User Type";
        }
    }

    // retrieve all rows from a table

    function get_rows_from_table($table, $conn){
        $sql = "SELECT * FROM $table";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0){
            return $result;
        }
        else{
            return null;
        }
    }

    // get all rows from a table where $wherewhat is $wherewho
    // for example to get all thesis of teacher with key/email "teacher"
    // $wherewhat should be "email" and $wherewho should be "teacher"

    function get_rows_from_table_where($table, $wherewhat, $wherewho, $conn){
        $sql = "SELECT * FROM $table WHERE $wherewhat = '$wherewho'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0){
            return $result;
        }
        else{
            return null;
        }
    }

    // update table named $table
    // set column $colname to value $colvalue
    // $wherewhat and $wherewho work the same as in the above function

    function update_table_row($table, $colname, $colvalue, $wherewhat, $wherewho, $conn){
        $sql = "UPDATE $table SET $colname = '$colvalue' WHERE $wherewhat = '$wherewho'";
        mysqli_query($conn, $sql);
    }

    // thesis logic

    function create_thesis($teacher, $title, $description, $conn){
        $sql = "INSERT INTO thesis VALUES(DEFAULT, '$teacher', '$title', '$description')";
        mysqli_query($conn, $sql);
    }


?>
