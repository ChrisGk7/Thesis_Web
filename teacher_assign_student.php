<?php
    session_start();
    include("header.html");
    include("database.php");
    include("teacher_header.html");
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
    <title>Assign Thesis to Student</title>
</head>
<body>
    
</body>
</html>

<?php
    $student_email;

    if (isset($_SESSION["studentemail"])){
        $student_email = $_SESSION["studentemail"];
    }
    else{
        header("Location: teacher_assign_student_list.php");
    }

    // all thesis made by this teacher

    $thesis_rows = get_rows_from_table_where("thesis", "teacher", $email, $conn);

    // all rows in student thesis relation in which the thesis is active

    
    $row_num = 0;
    
    echo "<br><br>Student Email: {$student_email}<br>";
    echo "Choose Thesis to Assign: <br><br>";
    
    if (mysqli_num_rows($thesis_rows) > 0){
        $row_assoc = array();
        while ($row = mysqli_fetch_assoc($thesis_rows)){
            
            $stu_thesis_relation = get_rows_from_table_condition("student_thesis_relation", "NOT status = 'canceled'", $conn);
            $row_assoc[(string)$row_num] = $row["id"];

            if (isset($_POST['assignbutton'.(string)$row_num])){
                
                add_to_student_thesis_relation($student_email, $row_assoc[(string)$row_num], $conn);
                header("Location: teacher_assign_student_list.php");
                break;
            }
            else{           

                // show thesis information

                echo "Thesis ID: " . $row_num . "<br>";
                //echo "Real Thesis ID: " . $row_assoc[(string)$row_num] . "<br>";
                echo "Thesis Title: {$row["title"]}" . "<br>";
                echo "Thesis Description: <br> {$row["description"]}" . "<br><br>";
                
                // true if there is an active thesis
                $found_row = false;

                // check if thesis can be assigned 
                // if a thesis is in stu_thesis_relation then it is assigned to a different
                // student and thus cannot be assigned to someone else\

                if (mysqli_num_rows($stu_thesis_relation) > 0){
                    while($stu_thesis_relation_row = mysqli_fetch_assoc($stu_thesis_relation)){
    
                        // check if the thesis is in the relation table
                        
                        if ($row["id"] == $stu_thesis_relation_row["thesis_id"]){
                            $found_row = true;
                        }
                    }
                    if ($found_row){
                        echo "This Thesis is Assigned to a different Student <br>";
                    }
                    else{
                        echo 
                            '<form action="teacher_assign_student.php" method="post">
                            <input type="submit" name="assignbutton'.$row_num.'" value="Assign This Thesis to the student with email: '.$student_email.'"<br>
                            </form>';
                    }
                }
                else{
                    echo 
                    '<form action="teacher_assign_student.php" method="post">
                    <input type="submit" name="assignbutton'.$row_num.'" value="Assign This Thesis to the student with email: '.$student_email.'"<br>
                    </form>';
                }
                // Assign This Thesis to This Student

                $row_num ++;
            
            }
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