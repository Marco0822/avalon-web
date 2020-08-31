<?php

require_once('phpstuff/connectDB.php');

if (isset($_POST['refresh'])){
    $gameID = $_POST['PHPgameID'];
    $username = $_POST['PHPusername'];
    //First element of this array will be the noOfVotes
    $playersList = "";
    $playersListWithVote = "";
    $playersAndChars = "";

    $charactersList = "||";
    $noOfVotes = 0;

    $yourChar = "||";


    $sql = "SELECT * FROM Players WHERE gameID=?"; 
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("s", $gameID);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result

    if (mysqli_num_rows($result) > 0) {
        
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            global $noOfVotes;
            global $playersList;
            global $charactersList;
            global $playersAndChars;

            $playersList .= $row["Username"];
            $playersList .= "//";

            $charactersList .= $row["IdentityNo"];
            $charactersList .= "//";

            if (($row['IsVoted'] == "agree") or ($row['IsVoted'] == "agree_hide") or ($row['IsVoted'] == "disagree") or ($row['IsVoted'] == "disagree_hide")){
                $noOfVotes = $noOfVotes + 1;
            }   
        }
        
        $playersListWithVote .= strval($noOfVotes);
        $playersListWithVote .= "//";
        $playersListWithVote .= $playersList;

        echo $playersListWithVote;
        echo $charactersList;

        
    } else {
        echo "0 results";
    }

    //Find your own Character 
    $sql = "SELECT * FROM Players WHERE gameID=? AND Username=?"; 
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("ss", $gameID, $username);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result

    if (mysqli_num_rows($result) > 0) {
        
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $yourChar .= $row["IdentityNo"];    
        }
        echo $yourChar;

    } else {
        echo "0 results";
    }


    //Get mission info for $missionPlayers
    $playersInEveryMission = "||";
    $resultsInEveryMission = "||";
    $noToBeAdded = ["zero_gibberish", "_one", "_two", "_three", "_four", "_five"];

    for ($x = 1; $x < 6; $x++) {
        $gameIDPlusNo = $gameID;
        $gameIDPlusNo .= $noToBeAdded[$x];
        $sql = "SELECT * FROM Players WHERE gameID=?"; 
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("s", $gameIDPlusNo);
        $stmt->execute();
        $result = $stmt->get_result(); // get the mysqli result

        $row = mysqli_fetch_array($result, MYSQLI_BOTH);
        $playersInEveryMission .= $row["Username"];
        $playersInEveryMission .= "//";

        $resultsInEveryMission .= $row ["IdentityNo"];
        $resultsInEveryMission .= "//";
        
       
    }
    echo $playersInEveryMission;
    echo $resultsInEveryMission;

}

