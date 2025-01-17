<?php
    session_start();
    include("header.html");
    include("database.php");
    include("teacher_header.html");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thesis Edit</title>
</head>
<body>
    
</body>
</html>


<?php

    $email;
    $thesisid;

    if (isset($_SESSION["email"])){
        $email = $_SESSION["email"];
        if (isset($_SESSION["thesisid"])){
            $thesisid = $_SESSION["thesisid"];
            //echo"here";
        }
        else{
            header("Location: teacher.php");    
        }
    }
    else{
        header("Location: index.php");
    }

    echo "<br><br><br>Email is: {$email} and thesisid is: {$thesisid} <br><br><br>";

    $current_thesis = get_rows_from_table_where("thesis", "id", $thesisid, $conn);
    $current_thesis_title;
    $current_thesis_desc;

    if ($current_thesis){
        while ($row = mysqli_fetch_assoc($current_thesis)){
            echo "Current Thesis Title: ";
            echo $row["title"] . "<br>";

            echo "Current Thesis Description: <br>";
            echo $row["description"] . "<br><br>";

            $current_thesis_title = $row["title"];
            $current_thesis_desc = $row["description"];

        }
    }

?>


<body>
    <br><br>
    Edit Thesis
    <form action="edit_thesis.php" method="post">
        <label>Thesis Title: </label><br>
        <input type="text" name="title" value=<?php echo $current_thesis_title?> placeholder="title" required><br>
        <label>Thesis Description: </label><br>
        <textarea type="text" name="desc" cols="40" rows="5" value=<?php echo $current_thesis_desc?> placeholder="description"maxlength="254" required></textarea><br>
        <input type="submit" name="enter" value="Enter">
    </form>
</body>
</html>

<?php

    if (isset($_POST["enter"])){
        
        $title_check = isset($_POST["title"]);
        $desc_check = isset($_POST["desc"]);
        
        if ($title_check && $desc_check){
            
            $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $desc = filter_input(INPUT_POST, "desc", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            //$title = $_POST["title"];
            //$desc = $_POST["desc"];
            
            update_table_row("thesis", "title", $title, "id", $thesisid, $conn);
            update_table_row("thesis", "description", $desc, "id", $thesisid, $conn);
        }
        else{
            echo "Please enter all fields";
        }
        
        // clear post after entering thesis or it gets readed on every refresh
        
        //unset($_POST["enter"]);

        header("Location: edit_thesis.php");
            
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