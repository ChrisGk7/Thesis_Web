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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher</title>
</head>
<body>
</body>
</html>

<!-- check if user is a teacher -->

<?php
    //session_start();

    echo "<br><br>Teacher Page<br>";
    //echo $_POST["email"];
    
    /*
    if (isset($_POST["logout"])){
        session_destroy();
        header("Location: index.php");
    }
    */

    if(check_user_type($_SESSION['email'], $conn) == "teacher"){
        echo "Email is: ";
        echo $_SESSION['email']; // display the message

    }
    else{
        header("Location: index.php");
    }

    $email = $_SESSION["email"];
?>


<!-- create new thesis -->


<body>
    <br><br>
    Create New Thesis
    <form action="teacher.php" method="post">
        <label>Thesis Title: </label><br>
        <input type="text" name="title" placeholder="name" required><br>
        <label>Thesis Description: </label><br>
        <textarea type="text" name="desc" cols="40" rows="5"placeholder="description"maxlength="254" required></textarea><br>
        <input type="submit" name="enter" value="Enter">
    </form>
</body>
</html>

<?php

    if (isset($_POST["enter"])){
        
        $title_check = isset($_POST["title"]);
        $desc_check = isset($_POST["desc"]);
        
        if ($title_check && $desc_check){

            if ($_POST["title"] != "" && $_POST["desc"] != ""){

                $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_SPECIAL_CHARS);
                $desc = filter_input(INPUT_POST, "desc", FILTER_SANITIZE_SPECIAL_CHARS);
                //$title = $_POST["title"];
                //$desc = $_POST["desc"];
                
                create_thesis($email, $title, $desc, $conn);
            }

            else{
                echo "Please enter all fields";    
            }
            
        }
        else{
            echo "Please enter all fields";
        }
        
        // clear post after entering thesis or it gets readed on every refresh
        
        //unset($_POST["enter"]);

        header("Location: teacher.php");
            
    }

?>




<!-- show all thesis -->

<?php
    $thesis_rows = get_rows_from_table_where("thesis", "teacher", $email, $conn);
    
    /*
    echo "post: ";
    echo $_POST['editbutton0'] . "<br>";
    */
    $row_num = 0;

    echo "My Thesis: <br><br>";

    if ($thesis_rows){
        $row_assoc = array();
        while ($row = mysqli_fetch_assoc($thesis_rows)){
            
            $row_assoc[(string)$row_num] = $row["id"];
            //echo $_POST['editbutton'.(string)$row_num];
            //echo "pre if <br>";
            //echo "row_num: ".$row_num."<br>";
            //echo "row_assoc: ".$row_assoc[(string)$row_num]."<br>";
            //echo "isset: ".$_POST['editbutton'.(string)$row_num]."<br>";

            if (isset($_POST['editbutton'.(string)$row_num])){
                unset($_SESSION["thesisid"]);
                //echo "post if <br>";
                //echo "row_num: ".$row_num."<br>";
                //echo "row_assoc: ".$row_assoc[(string)$row_num]."<br>";
                $_SESSION["thesisid"] = $row_assoc[(string)$row_num];
                //echo "session: ".$_SESSION["thesisid"] . "<br>";
                //echo $row['id'] . "<br>";
                //echo "editbutton".(string)$i;
                //echo"got here";
                header("Location: edit_thesis.php");
                break;
            }
            elseif (isset($_POST['deletebutton'.(string)$row_num])){
                unset($_SESSION["thesisid"]);
                delete_table_row("thesis", "id", $row_assoc[(string)$row_num], $conn);
                header("Location: teacher.php");
                break;
            }
            else{           
            
                echo "Thesis ID: " . $row_num . "<br>";
                //echo "Real Thesis ID: " . $row_assoc[(string)$row_num] . "<br>";
                echo "Thesis Title: {$row["title"]}" . "<br>";
                echo "Thesis Description: <br> {$row["description"]}" . "<br><br>";
                
                // edit and delete buttons
                
                echo 
                '<form action="teacher.php" method="post">
                <input type="submit" name="editbutton'.$row_num.'" value="Edit Thesis">
                <input type="submit" name="deletebutton'.$row_num.'" value="Delete Thesis"<br>
                </form>';
                //echo "editbutton".$row_num."<br>";
                /*
                echo "post isset: ";
                echo $_POST['editbutton' . (string)$row_num] . "<br>";
                */
                $row_num ++;
            
            }
        }
    }
    
    /*
    for ($i = 0; $i < $row_num; $i++){

        echo "checking: editbutton".(string)$i."<br>";

    }
    */
    
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