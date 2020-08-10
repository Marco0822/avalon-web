<?php
require_once('phpstuff/connectDB.php');

//If Create Game button is pressed
if(array_key_exists('createGameFunction',$_POST)){

    //get username and gameId from input
    $username = $_POST['uid'];
    $gameID = $_POST['game-ID'];

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
            echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        }
        
        
        if (!$stmt->bind_param("ss", $gameID, $username)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        //Successfully entered gameId and username to database
        session_start();

        //store gameID and usernameID as global variables
        $_SESSION['gameID'] = $gameID;
        $_SESSION['uid'] = $username;

        //log Out Btn should be visible after created game successfully
        $_SESSION['logOutIsVisible'] = true;

        $stmt->close();
        header("location:index.php");
        exit();
    }
 }


 //If back(to index) button is pressed
 if(array_key_exists('backBtn',$_POST)){
    header("Location:index.php");
    echo "pressed back button";
    exit();
 }


?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Game Page</title>
</head>
<body>

<form method="post">
    <input name="game-ID" type="text" placeholder="Game ID:"><br>
    <input name="uid" type="text" placeholder="Username:"><br>
    <button type="submit" name="createGameFunction">Create Game</button>
    
    <!--back button-->
    <input type="submit" name="backBtn"
    class="button" value="Back"/> 
</form>


</body>
</html>