<?php
/*

if(isset($_POST['createGame'])){
    if($_POST['PHPgameID'] == 'hello'){
        echo "success";
    } else {
        echo "failure";
    }
} else {
    echo "not set Post variable";
}*/



//If Create Game button is pressed
if (isset($_POST['createGame'])){

    require_once('phpstuff/connectDB.php');

    //get username and gameId from input
    $username = $_POST['PHPusername'];
    $gameID = $_POST['PHPgameID'];

    //Check if desired gameID name is taken
    $sql = "SELECT * FROM Players WHERE gameID=?"; 
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("s", $gameID);
    $stmt->execute();
    $result = $stmt->get_result();  

    //If gameID is taken
    if (mysqli_num_rows($result) !== 0) { 
        echo "Game ID has already been taken";
        
    //Else try to insert gameID and username into table Players
    } else {
    
        if (!($stmt = $conn->prepare("INSERT INTO Players(gameID, Username) VALUES (?, ?)"))) {
            echo ("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }
        
        
        if (!$stmt->bind_param("ss", $gameID, $username)) {
            echo ("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
        }
        
        if (!$stmt->execute()) {
            echo ("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }
        //Successfully entered gameId and username to database
        session_start();

        //store gameID and usernameID as global variables
        $_SESSION['gameID'] = $gameID;
        $_SESSION['uid'] = $username;

        //log Out Btn should be visible after created game successfully
        $_SESSION['logOutIsVisible'] = true;

        $stmt->close();
        echo "Inserted data successfully!";
    }
}
