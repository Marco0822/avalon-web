<?php
require_once('phpstuff/connectDB.php');

//If join Game button is pressed
if(array_key_exists('joinGameFunction',$_POST)){

    //get username and gameId from input
    $username = $_POST['uid'];
    $gameID = $_POST['game-ID'];

    echo $username;
    echo '<br>';
    echo $gameID;
    echo "stuff";

    //Check if gameID name is created
    $sql = "SELECT * FROM Players WHERE gameID=?"; 
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("s", $gameID);
    $stmt->execute();
    $result = $stmt->get_result();  

    //If gameID has not been created yet
    if (mysqli_num_rows($result) == 0) { 
        header("location:joinPage.php?error=gameIDNotCreated");
        exit();
    //Else if gameID has been created, insert gameID and username into table Players
    } else {

        //Throw error if username has already been used
        $sql = "SELECT * FROM Players WHERE gameID=? AND Username=?"; 
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("ss", $gameID, $username);
        $stmt->execute();
        $result = $stmt->get_result(); 
        //If username already taken
        if (mysqli_num_rows($result) !== 0) { 
            header("location:joinPage.php?error=usernameTaken");
            exit();
        }

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

        //log Out Btn should be visible after joined game successfully
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
    <button type="submit" name="joinGameFunction">Join Game</button>

    <!--back button-->
    <input type="submit" name="backBtn"
    class="button" value="Back"/> 
</form>


    
</body>
</html>