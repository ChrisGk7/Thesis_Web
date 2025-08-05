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
    <title>Assign Student</title>
</head>
<body>
    <br><br>
    Search By
    <form method="post">
        <select name="type" id="type" size="1">
            <option>Student Name</option>
            <option>Student AM</option>
        </select>
        <label>Input: </label><br>
        <input type="search" name="input" placeholder="Leave Empty to Show All Students" style="width: 300px;"><br><br>
        <label>Thesis Status</label>
        <select name="thesis_status" id="thesis_status" size="1">
            <option>All</option>
            <option>Pending Assignment</option>
            <option>Active</option>
            <option>Under Examination</option>
            <option>Finished</option>
            <option>Canceled</option>
        </select>
        <input type="submit" name="submit" value="Search">
    </form>
</body>
</html>


<?php

    $student_rows;
    $input;
    $thesis_status = "all";

    if (isset($_POST["submit"])){
        //echo $_POST["type"];

        $input = filter_input(INPUT_POST, "input", FILTER_SANITIZE_SPECIAL_CHARS);
    }
    
    if (isset($_POST["submit"])){
        if ($_POST["input"] == ""){
            $student_rows = get_rows_from_table("student", $conn);
            
        }
        elseif ($_POST["type"] == "Student Name"){
            $sname = filter_input(INPUT_POST, "input", FILTER_SANITIZE_SPECIAL_CHARS);
            $sql = "SELECT * FROM student WHERE email in (SELECT email FROM user WHERE name LIKE '%".$sname."%')";
            $student_rows = mysqli_query($conn, $sql);
        }
        elseif ($_POST["type"] == "Student AM"){
            $sam = filter_input(INPUT_POST, "input", FILTER_SANITIZE_SPECIAL_CHARS);
            $student_rows = get_rows_from_table_where("student", "am", $sam, $conn);
            }
        

        if ($_POST["thesis_status"] == "All"){
            $thesis_status = "all";
        }
        elseif ($_POST["thesis_status"] == "Pending Assignment"){
            $thesis_status = "pending_assignment";
        }
        elseif ($_POST["thesis_status"] == "Active"){
            $thesis_status = "active";
        }
        elseif ($_POST["thesis_status"] == "Under Examination"){
            $thesis_status = "being_examined";
        }
        elseif ($_POST["thesis_status"] == "Finished"){
            $thesis_status = "finished";
        }
        elseif ($_POST["thesis_status"] == "Canceled"){
            $thesis_status = "canceled";
        }  
    }
    else{
        $student_rows = get_rows_from_table("student", $conn);
    }
                
    show_students($student_rows, $thesis_status, $conn);

    function show_students($student_rows, $thesis_status, $conn){
        $row_num = 0;

        if ($student_rows){
            $row_assoc = array();
            while ($row = mysqli_fetch_assoc($student_rows)){

                $row_assoc[(string)$row_num] = $row["email"];

                if (isset($_POST['assignbutton'.(string)$row_num])){
                    unset($_SESSION["studentemail"]);
                    $_SESSION["studentemail"] = $row_assoc[(string)$row_num];
                    
                    header("Location: teacher_assign_student.php");
                    break;
                }
                elseif (isset($_POST['cancelbutton'.(string)$row_num])){
                    unset($_SESSION["studentemail"]);
                    update_table_row_condition("student_thesis_relation", "status", "canceled", "stu_email = '".$row["email"]."' AND NOT status = 'canceled'", $conn);
                    header("Location: teacher_assign_student_list.php");
                    break;
                }
                elseif (isset($_POST['activatebutton'.(string)$row_num])){
                    //unset($_SESSION["studentemail"]);
                    update_table_row_condition("student_thesis_relation", "status", "being_examinated", "stu_email = '".$row["email"]."' AND status = 'active'", $conn);
                    header("Location: teacher_assign_student_list.php");
                    break;
                }
                
   
                // check if a thesis can be assigned to the student
                // if not don't show the student

                // row from thesis relation where student has an active thesis
                // otherwise null

                $students_active_thesis = get_rows_from_table_condition("student_thesis_relation", "stu_email = '".$row["email"]."' AND NOT status = 'canceled'", $conn);
                
                
                // show student with no assigned thesis or canceled thesis
                // therefore this student can be assigned a thesis

                echo "<hr><hr><hr>";
                echo "Student Name:" . mysqli_fetch_assoc(get_rows_from_table_where("user", "email", $row["email"], $conn))["name"] . "<br>";
                echo "Student Email: " . $row["email"] . "<br>";
                echo "Student AM: " . $row["am"] . "<br>";
                echo "Student Street: " . $row["street"] . "<br>";
                echo "Student Street Number: " . $row["number"] . "<br>";
                echo "Student City: " . $row["city"] . "<br>";
                echo "Student Postcode: " . $row["postcode"] . "<br>";
                echo "Student' Father Name: " . $row["father_name"] . "<br>";
                echo "Student Mobile Phone: " . $row["cellphone"] . "<br>";
                echo "Student Landline: " . $row["phone"] . "<br>";

                // if thesis can be assigned to this student create button

                if (!(mysqli_num_rows($students_active_thesis) > 0)){
                    echo 
                    '<form action="teacher_assign_student_list.php" method="post">
                    <input type="submit" name="assignbutton'.$row_num.'" value="Assign a Thesis to this Student">
                    </form>';    
                }
                else{
                    echo "<br>You cannot assign a thesis to this student because they are already assigned to a thesis<br>";
                }

                // get all students from thesis relation with an active thesis.
                // show only the ones that are related to this teacher in one of 3 ways
                // this teacher created the thesis
                // this teacher is assigned as examiner 1
                // this teacher is assigned as examiner 2
                // in any case this teacher is allowed to cancel the thesis

                // this should by checked on a by thesis basis

                // save the logged in teacher information

                $teacher_user = get_rows_from_table_where("user", "email", $_SESSION["email"], $conn);
                $teacher = get_rows_from_table_where("teacher", "email", $_SESSION["email"], $conn);

                $teacher_user_row = mysqli_fetch_assoc($teacher_user);
                $teacher_row = mysqli_fetch_assoc($teacher);

                // get all thesis this teacher has made
                // if a student in thesis relation is assigned a thesis by
                // this teacher then teacher is elligible

                $thesis_by_this_teacher = get_rows_from_table_where("thesis", "teacher", $_SESSION["email"], $conn);

                // first check for thesis made by the logged in teacher
                
                while ($thesis_by_this_teacher_row = mysqli_fetch_assoc($thesis_by_this_teacher)){
                    
                    // returns the rows from thesis relation where the currently 
                    // checked student is assigned a thesis that is made by
                    // the currently logged in teacher

                    $student_thesis = get_rows_from_table_condition("student_thesis_relation", "stu_email = '".$row["email"]."' AND thesis_id = '".$thesis_by_this_teacher_row["id"]."'", $conn);

                    if (!($thesis_status == "all")){
                        $student_thesis = get_rows_from_table_condition("student_thesis_relation", "stu_email = '".$row["email"]."' AND thesis_id = '".$thesis_by_this_teacher_row["id"]."' AND status = '".$thesis_status."'", $conn);
                    }

                    if (mysqli_num_rows($student_thesis) > 0){

                        while ($student_thesis_row = mysqli_fetch_assoc($student_thesis)){
                            
                            show_thesis($student_thesis_row, $thesis_by_this_teacher_row, $teacher_row, $teacher_user_row, $row_num, $conn);
                        }

                    }
                }

                // then check for thesis for which this teacher has been an examiner

                $teacher_examiner = get_rows_from_table_condition("student_thesis_relation", "stu_email = '".$row["email"]."' AND teach1_email = '".$_SESSION["email"]."'", $conn);

                if (!($thesis_status == "all")){
                    $teacher_examiner = get_rows_from_table_condition("student_thesis_relation", "stu_email = '".$row["email"]."' AND teach1_email = '".$_SESSION["email"]."' AND status = '".$thesis_status."'", $conn);
                }

                while ($teacher_examiner_row = mysqli_fetch_assoc($teacher_examiner)){

                    $related_thesis = get_rows_from_table_where("thesis", "id", $teacher_examiner_row["thesis_id"], $conn);
                    $related_thesis_row = mysqli_fetch_assoc($related_thesis);
                    $related_teacher = get_rows_from_table_where("user", "email", $related_thesis_row["teacher"], $conn);

                    show_thesis($teacher_examiner_row, $related_thesis_row, $teacher_row, mysqli_fetch_assoc($related_teacher), $row_num, $conn);
                }

                // check again for examiner 2

                $teacher_examiner = get_rows_from_table_condition("student_thesis_relation", "stu_email = '".$row["email"]."' AND teach2_email = '".$_SESSION["email"]."'", $conn);

                if (!($thesis_status == "all")){
                    $teacher_examiner = get_rows_from_table_condition("student_thesis_relation", "stu_email = '".$row["email"]."' AND teach2_email = '".$_SESSION["email"]."' AND status = '".$thesis_status."'", $conn);
                }

                while ($teacher_examiner_row = mysqli_fetch_assoc($teacher_examiner)){

                    $related_thesis = get_rows_from_table_where("thesis", "id", $teacher_examiner_row["thesis_id"], $conn);
                    $related_thesis_row = mysqli_fetch_assoc($related_thesis);
                    $related_teacher = get_rows_from_table_where("user", "email", $related_thesis_row["teacher"], $conn);

                    show_thesis($teacher_examiner_row, $related_thesis_row, $teacher_row, mysqli_fetch_assoc($related_teacher), $row_num, $conn);
                }
      
            $row_num ++;
            }
        }
        else{
            echo "<br>No students found with your current search";
        }
    }


    function show_thesis($students_thesis_row, $thesis_row, $teacher_row, $teacher_user_row, $row_num, $conn){
            echo "<hr>";
            
            // show assigned thesis creator info
            
            echo "Real Thesis ID: " .$thesis_row["id"]. "<br>";
            echo "Thesis Creator Name: " .$teacher_user_row["name"]. "<br>";
            echo "Thesis Creator Topic: " .$teacher_row["topic"]. "<br>";
            echo "Thesis Creator Landline: " .$teacher_row["landline"]. "<br>";
            echo "Thesis Creator Mobile: " .$teacher_row["mobile"]. "<br>";
            echo "Thesis Creator Department: " .$teacher_row["department"]. "<br>";
            echo "Thesis Creator University: " .$teacher_row["university"]. "<br><br>";
            
            // show assigned thesis info
            
            echo "Thesis Title: {$thesis_row["title"]}" . "<br>";
            echo "Thesis Description: <br> {$thesis_row["description"]}" . "<br><br>";
            echo "Thesis Examiner 1: ".$students_thesis_row["teach1_email"]. "<br>";
            
            if ($students_thesis_row["teach1_email"]){
                show_teacher($students_thesis_row["teach1_email"], $conn);
            }

            echo "Thesis Examiner 2: ".$students_thesis_row["teach2_email"]. "<br>";

            if ($students_thesis_row["teach2_email"]){
                show_teacher($students_thesis_row["teach2_email"], $conn);
            }

            echo "Thesis Status: ".$students_thesis_row["status"]. "<br>";
            echo "Thesis Grade: ".$students_thesis_row["grade"]. "<br>";
            
            // if thesis is active show button to cancel it, else 
            // show that it's already canceled
            
            if ($students_thesis_row["status"] == "canceled"){
                echo "<br>";
            }
            elseif ($teacher_row["email"] == $_SESSION["email"]){
                echo 
                '<form action="teacher_assign_student_list.php" method="post">
                <input type="submit" name="cancelbutton'.$row_num.'" value="Cancel This Thesis"<br><br>
                </form>';

                if ($students_thesis_row["status"] == "active"){
                    echo 
                    '<form action="teacher_assign_student_list.php" method="post">
                    <input type="submit" name="activatebutton'.$row_num.'" value="Set this Thesis as Under Examination"<br><br>
                    </form>';
                }
            }

    }

    function show_teacher($teacher_email, $conn){

        $teacher_row = mysqli_fetch_assoc(get_rows_from_table_where("teacher", "email", $teacher_email, $conn));
        $teacher_user_row = mysqli_fetch_assoc(get_rows_from_table_where("user", "email", $teacher_email, $conn));

        echo "Teacher Name: " .$teacher_user_row["name"]. "<br>";
        echo "Teacher Topic: " .$teacher_row["topic"]. "<br>";
        echo "Teacher Landline: " .$teacher_row["landline"]. "<br>";
        echo "Teacher Mobile: " .$teacher_row["mobile"]. "<br>";
        echo "Teacher Department: " .$teacher_row["department"]. "<br>";
        echo "Teacher University: " .$teacher_row["university"]. "<br><br>";
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