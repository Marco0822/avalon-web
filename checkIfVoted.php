<?php

if (isset($_POST['vote'])){
    require_once('phpstuff/connectDB.php');
    
    $gameID = $_POST['PHPgameID'];
    $username = $_POST['PHPusername'];

    // Check if user has gameID
    if ($gameID == "not yet set") {
        echo ("Unable to vote, create or enter game first!");
    } else {
        $agree = "agree";
        $disagree ="disagree";
        $sql = "SELECT * FROM Players WHERE (gameID=? OR gameID=?) AND Username=?"; // SQL with parameters
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("sss", $agree, $disagree, $username);
        $stmt->execute();
        $result = $stmt->get_result(); // get the mysqli result
        $row = $result->fetch_assoc(); // fetch data   
        $stmt->close();

        //If voted already, throw error
        if (mysqli_num_rows($result) !== 0) { 
            echo("votedAlready");
            
        //Else, go to vote page 
        } else {
            echo("not voted yet, can vote now");
        }
    }
}

