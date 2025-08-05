<?php
    
    session_start();
    include("header.html");
    include("database.php");
    include("teacher_header.html");
    if (isset($_SESSION["thesisid"])){
        unset($_SESSION["thesisid"]);
    }
    if (isset($_SESSION["studentemail"])){
        unset($_SESSION["studentemail"]);
    }
?>

<!-- check if user is a teacher -->

<?php
    //session_start();

    //echo "<br><br>Teacher Page<br>";
    //echo $_POST["email"];
    
    /*
    if (isset($_POST["logout"])){
        session_destroy();
        header("Location: index.php");
    }
    */

    if(check_user_type($_SESSION['email'], $conn) == "teacher"){
        //echo "Email is: ";
        //echo $_SESSION['email']; // display the message

    }
    else{
        header("Location: index.php");
    }

    $email = $_SESSION["email"];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Manager</title>
</head>
<body>
    
</body>
</html>

<?php
    echo "<br> Email: {$_SESSION['email']}<br><br>";

    // get and show all requests associated with the logged in teacher

    $request = get_rows_from_table_where("request", "teach_email", $email, $conn);

    if ($request){

        $row_num = 0;

        while ($request_row = mysqli_fetch_assoc($request)){

            // get info on student, thesis, and teacher to display

            $student_email = $request_row["stu_email"];
            $thesis_id = $request_row["thesis_id"];
            $teacher_email = (mysqli_fetch_assoc(mysqli_query($conn, "SELECT teacher FROM thesis WHERE id = '".$thesis_id."'")))["teacher"];

            $student_user_row = mysqli_fetch_assoc(get_rows_from_table_where("user", "email", $student_email, $conn));
            $student_row = mysqli_fetch_assoc(get_rows_from_table_where("student", "email", $student_email, $conn));

            $thesis_row = mysqli_fetch_assoc(get_rows_from_table_where("thesis", "id", $thesis_id, $conn));

            $teacher_user_row = mysqli_fetch_assoc(get_rows_from_table_where("user", "email", $teacher_email, $conn));
            $teacher_row = mysqli_fetch_assoc(get_rows_from_table_where("teacher", "email", $teacher_email, $conn));

            $stu_thesis_relation_row = mysqli_fetch_assoc(get_rows_from_table_condition("student_thesis_relation", "stu_email = '".$student_email."' AND thesis_id = '".$thesis_id."' AND status = 'pending_assignment'", $conn));
            
            // button logic

            if (isset($_POST['acceptbutton'.(string)$row_num])){
                
                acc_decl_request($student_email, $email, $thesis_id, "accepted", $conn);
                add_examiner_to_thesis($thesis_id, $email, $student_email, $conn);
                sleep(0.2);
                auto_cancel_requests($thesis_id, $student_email, $conn);
                
                header("Location: teacher_thesis_request.php");
                break;
            }
            elseif (isset($_POST['declinebutton'.(string)$row_num])){
                
                acc_decl_request($student_email, $email, $thesis_id, "declined", $conn);

                header("Location: teacher_thesis_request.php");
                break;
            }

            // show student info

            echo "<hr><hr>";
            echo "A student named ".$student_user_row["name"]." has requested you as an examiner on their thesis.<br>";
            echo "Student Email: ".$student_email."<br>";
            echo "Student AM: ".$student_row["am"]."<br>";
            echo "Student Mobile: ".$student_row["cellphone"]."<br><br>";

            echo "The thesis creator is named: ".$teacher_user_row["name"]."<br>";
            echo "Thesis Creator email: ".$teacher_email."<br>";
            echo "Thesis Creator Topic: ".$teacher_row["topic"]."<br>";
            echo "Thesis Creator Mobile: ".$teacher_row["mobile"]."<br>";
            echo "Thesis Creator Department: ".$teacher_row["department"]."<br>";
            echo "Thesis Creator University: ".$teacher_row["university"]."<br><br>";

            echo "Thesis Title: ".$thesis_row["title"]."<br>";
            echo "Thesis Description:<br>".$thesis_row["description"]."<br><br>";

            echo "Request Status: ".$request_row["status"]."<br>";

            // show button only if the status is pending

            if ($request_row["status"] == "pending"){

                echo
                '<form action="teacher_thesis_request.php" method="post">
                <input type="submit" name="acceptbutton'.$row_num.'" value="Accept request as an Examiner">
                <input type="submit" name="declinebutton'.$row_num.'" value="Decline request as an Examiner">
                </form>';  
            }
            else{
                echo "Sorry but this thesis already has 3 examiners<br>";
            }

            $row_num++;


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