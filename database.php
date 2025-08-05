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
        return $result;

        
    }

    function get_rows_from_table_condition($table, $condition, $conn){
        $sql = "SELECT * FROM $table WHERE $condition";
        $result = mysqli_query($conn, $sql);
        return $result;
    }

    // get all rows from a table where $wherewhat is $wherewho
    // for example to get all thesis of teacher with key/email "teacher"
    // $wherewhat should be "email" and $wherewho should be "teacher"

    function get_rows_from_table_where($table, $wherewhat, $wherewho, $conn){
        $sql = "SELECT * FROM $table WHERE $wherewhat = '$wherewho'";
        $result = mysqli_query($conn, $sql);
        return $result;
    }

    // update table named $table
    // set column $colname to value $colvalue
    // $wherewhat and $wherewho work the same as in the above function

    function update_table_row($table, $colname, $colvalue, $wherewhat, $wherewho, $conn){
        $sql = "UPDATE $table SET $colname = '$colvalue' WHERE $wherewhat = '$wherewho'";
        mysqli_query($conn, $sql);
    }

    function update_table_row_condition($table, $colname, $colvalue, $condition, $conn){
        $sql = "UPDATE $table SET $colname = '$colvalue' WHERE $condition";
        mysqli_query($conn, $sql);
    }

    function delete_table_row($table, $wherewhat, $wherewho, $conn){
        $sql = "DELETE FROM $table WHERE $wherewhat = '$wherewho'";
        mysqli_query($conn, $sql);
    }

    // thesis logic

    function create_thesis($teacher, $title, $description, $conn){
        $sql = "INSERT INTO thesis VALUES(DEFAULT, '$teacher', '$title', '$description')";
        mysqli_query($conn, $sql);
    }

    function add_to_student_thesis_relation($student_email, $thesis_id, $conn){
        $sql = "INSERT INTO student_thesis_relation VALUES('$student_email', '$thesis_id', DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT)";
        mysqli_query($conn, $sql);
    }

    function acc_decl_request($student_email, $teacher_email, $thesis_id, $status, $conn){
        $sql = "UPDATE request SET status = '".$status."' WHERE stu_email = '".$student_email."' AND teach_email = '".$teacher_email."' AND thesis_id = '".$thesis_id."' AND status = 'pending'";
        mysqli_query($conn, $sql);
        $sql = "UPDATE request SET reply_datetime = CURRENT_TIMESTAMP() WHERE stu_email = '".$student_email."' AND teach_email = '".$teacher_email."' AND thesis_id = '".$thesis_id."' AND status = '".$status."'";
        mysqli_query($conn, $sql);
    }

    function add_examiner_to_thesis($thesis_id, $teacher_email, $student_email, $conn){
        
        // get the student thesis relation row and check for examiner availiability

        $sql = "SELECT * FROM student_thesis_relation WHERE stu_email = '".$student_email."' AND thesis_id = '".$thesis_id."' AND status = 'pending_assignment'";
        $thesis_relation_row = mysqli_fetch_assoc(mysqli_query($conn, $sql));

        if (!$thesis_relation_row["teach1_email"]){
            $sql = "UPDATE student_thesis_relation SET teach1_email = '".$teacher_email."' WHERE stu_email = '".$student_email."' AND thesis_id = '".$thesis_id."' AND status = 'pending_assignment'";
        }
        elseif (!$thesis_relation_row["teach2_email"]){
            $sql = "UPDATE student_thesis_relation SET teach2_email = '".$teacher_email."' WHERE stu_email = '".$student_email."' AND thesis_id = '".$thesis_id."' AND status = 'pending_assignment'";
        }
        mysqli_query($conn, $sql);

    }

    function auto_cancel_requests($thesis_id, $student_email, $conn) {

        $sql = "SELECT * FROM student_thesis_relation WHERE stu_email = '".$student_email."' AND thesis_id = '".$thesis_id."' AND status = 'pending_assignment'";
        $thesis_relation_row = mysqli_fetch_assoc(mysqli_query($conn, $sql));

        if ($thesis_relation_row["teach1_email"] && $thesis_relation_row["teach2_email"]){
            update_table_row_condition("student_thesis_relation", "status", "active", "stu_email = '".$student_email."' AND thesis_id = '".$thesis_id."' AND status = 'pending_assignment'", $conn);
            
            // once the thesis is accepted auto decline all the other requests

            $sql = "UPDATE request SET status = 'declined', reply_datetime = CURRENT_TIMESTAMP() WHERE stu_email = '".$student_email."' AND thesis_id = '".$thesis_id."' AND status = 'pending'";
            mysqli_query($conn, $sql);

        }
    }


?>