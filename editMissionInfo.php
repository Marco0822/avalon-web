<?php



require_once('phpstuff/connectDB.php');

//Zero is gibberish because it will not be used
$noToBeAdded = ["zero_gibberish", "_one", "_two", "_three", "_four", "_five"];

//If go button is pressed
if (isset($_POST['mission'])){
    $gameID = $_POST['gameID'];
    $gameIDPlusNo = $gameID;

    $missionToEdit = $_POST['missionToEdit'];
    $missionPlayers = $_POST['missionPlayers'];
    $missionResult = $_POST['missionResult'];
    $gameIDPlusNo .= $noToBeAdded[$missionToEdit];
    echo $gameIDPlusNo;
    echo ",";
    echo $missionPlayers;
    echo ",";
    echo $missionResult;

    

    $sql = "UPDATE Players SET IdentityNo=?, Username=? WHERE gameID=?";
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("sss", $missionResult, $missionPlayers, $gameIDPlusNo);
    $stmt->execute();


}