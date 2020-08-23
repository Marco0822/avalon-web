<?php

session_start();
$gameID = $_SESSION['gameID'];
$username = $_SESSION['uid'];


$characterArray = json_decode($_POST['charArray']);

if (isset($_POST['charArray'])){
    require_once('phpstuff/connectDB.php'); 
    $sql = "SELECT * FROM Players WHERE gameID=?"; 
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("s", $gameID);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result

    $playerArray = array();
    while ($row = mysqli_fetch_row($result)) {
        array_push($playerArray, $row[2]);
    }

    if (mysqli_num_rows($result) > 0){ //There's data in the database
        $noOfPlayers = mysqli_num_rows($result);
    } else {
        echo "No Players";
    }

    if ($noOfPlayers == count($characterArray)){
    
        for ($x = 0; $x < $noOfPlayers; $x++) {
            $identity = $characterArray[$x];
            $playerUsername = $playerArray[$x];

            $sql = "UPDATE Players SET IdentityNo=? WHERE gameID=? AND Username=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $identity, $gameID, $playerUsername);
            $stmt->execute();
            if ($stmt->error) {
            echo "FAILURE!!! " . $stmt->error;
            }
            $stmt->close();
            
        }
        

        echo "The characters have been changed!";

    } else {
        echo "The number of Characters you selected doesn't match the number of Players!";
    }

   
} else {
    echo "error";
}
