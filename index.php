
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Just Avalon</title>
    <link rel="stylesheet" href="styles/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@500;700&display=swap" rel="stylesheet">
</head>
<body>

<?php
    
include_once("phpstuff/connectDB.php");

$createSql = "CREATE TABLE IF NOT EXISTS Players (
                id INT(11) PRIMARY KEY AUTO_INCREMENT,
                gameID VARCHAR(30),
                Username VARCHAR(100),
                IdentityNo VARCHAR(30),
                IsVoted VARCHAR(20)
                )";
$query = mysqli_query($conn, $createSql);


//If Create Game button is pressed
if (isset($_POST['createGame'])){

    require_once('connectDB.php');

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
        exit("Game ID has already been taken");
        
    //Else try to insert gameID and username into table Players
    } else {
    
        if (!($stmt = $conn->prepare("INSERT INTO Players(gameID, Username) VALUES (?, ?)"))) {
            exit("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }
        
        
        if (!$stmt->bind_param("ss", $gameID, $username)) {
            exit("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
        }
        
        if (!$stmt->execute()) {
            exit("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }
        //Successfully entered gameId and username to database
        session_start();

        //store gameID and usernameID as global variables
        $_SESSION['gameID'] = $gameID;
        $_SESSION['uid'] = $username;

        //log Out Btn should be visible after created game successfully
        $_SESSION['logOutIsVisible'] = true;

        $stmt->close();
        exit("Inserted data successfully!");
    }
}





    session_start();

//function to pop up message box and show message
    function echoAlert($text) {
        echo "<script type='text/javascript'>alert('{$text}');</script>";
    }

    // Set session[gameID] default to "not yet set"
    if (!(isset($_SESSION['gameID']))){
        $_SESSION['gameID'] = "not yet set";
    }

    // Set session[uid] default to "not yet set"
    if (!(isset($_SESSION['uid']))){
        $_SESSION['uid'] = "not yet set";
    }
    
    //Messages to be shown in the errorMessageLabel
    $error = "no error for now";

    // This function pops up message box, echoing $msg
    function alert($msg) {
        echo "<script type='text/javascript'>alert('$msg');</script>";
    }

    //Check for errors sent to the page
    if (isset($_GET['error'])){
        $error = $_GET['error'];
        
        //If error is no game ID, echo can't vote cause no gameID
        if ($error == "noGameID"){
            $error = "Unable to vote due to no GameID";
            //alert("Unable to vote due to no GameID");
        } 
        // Can't see result cause no gameID
        if ($error == "cannotSeeResult"){
            $error = "Unable to see results due to no GameID";
        } 
        // Can't start new game cause no gameID
        if ($error == "cannotStartGame"){
            $error = "Unable to start new game due to no GameID";
        } 

    }

    //Echo global variable gameID
    if(isset($_SESSION['gameID'])) {  
        $gameID = $_SESSION['gameID'];
        
    //Echo global variable uid
        if(isset($_SESSION['uid'])) {  
            $username = $_SESSION['uid'];
        }

    //Connect to DB

        require_once('phpstuff/connectDB.php'); 

        
        // Select data with the same gameID
        // Used for showing players with same gameID
        $sql = "SELECT * FROM Players WHERE gameID=?"; 
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("s", $gameID);
        $stmt->execute();
        $result = $stmt->get_result(); 
    
        // $datas array will contain all rows with the same gameID
        $datas = array();
        $noOfPlayers = 5;
        $threeDArray = array (
            array(2,3,2,3,3),
            array(2,3,4,3,4),
            array(2,3,3,"4*",4),
            array(3,4,4,"5*",5),
            array(3,4,4,"5*",5),
            array(3,4,4,"5*",5)
          );
        $arrayUsing = [2,3,2,3,3];

        //There's data in the database
        if (mysqli_num_rows($result) > 0){ 
            $noOfPlayers = mysqli_num_rows($result);

            //Even if players less than five, make it seem like it's five
            //So that the buttons showing the players need for each mission
            //would work properly
            if (mysqli_num_rows($result) < 5){ 
                $noOfPlayers = 5;
            }
            $arrayUsing = $threeDArray[$noOfPlayers-5];
            //still havin rows to fetch
            while ($row = mysqli_fetch_assoc($result)){ 
                $datas[] = $row;
            }
        }

    }

    // Check if there's any votes
    require_once('phpstuff/connectDB.php'); 
    $agree = "agree";
    $disagree = "disagree";
    $agree_hide = "agree_hide";
    $disagree_hide = "disagree_hide";
    $sql = "SELECT * FROM Players WHERE gameID=? AND (IsVoted=? OR IsVoted=? OR IsVoted=? OR IsVoted=?)"; 
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("sssss", $gameID, $agree, $disagree, $agree_hide, $disagree_hide);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result

    if (mysqli_num_rows($result) > 0){ //There's data in the database
        $noOfVotes = mysqli_num_rows($result);
    } else {
        $noOfVotes = "0";
    }

    //Get players for $playersInEveryMission
    $playersInEveryMission = [];
    $resultsInEveryMission = [];
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
        
        if (mysqli_num_rows($result) > 0){ 
            if ($row["Username"] !== null) {
                array_push($playersInEveryMission, $row["Username"]);
            }
            if ($row["IdentityNo"] !== null) {
                array_push($resultsInEveryMission, $row["IdentityNo"]);
            }
        }
        
        
    
    }
   


    //If log out button is pressed
    if(array_key_exists('logOutBtn', $_POST)) { 
    //Delete your own username from the database
    //Problem is that it deletes not only the user, but another user
    //with the same username with a different gameID
    $sql = "DELETE FROM Players WHERE Username='$username'";
    
     if ($conn->query($sql) === TRUE) {
       echo "Record deleted successfully";
       $_SESSION['gameID'] = "not yet set";
       $_SESSION['uid'] = "not yet set";
       $_SESSION['logOutIsVisible'] = false;
       header("Location:index.php");
       exit();
    
     } else {
       echo "Error deleting record: " . $conn->error;
     }
     
     $conn->close();
    
    }


require_once('phpstuff/connectDB.php'); 

//Find your Character
    $sql = "SELECT * FROM Players WHERE (gameID=? AND Username=?)"; 
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("ss", $gameID, $username);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result

    $yourCharArray = array();
    $yourChar = "";

    if (mysqli_num_rows($result) > 0){ //There's data in the database
        //still havin rows to fetch
        while ($row = mysqli_fetch_assoc($result)){ 
            $yourCharArray[] = $row;
        }
        $yourChar = $yourCharArray[0]["IdentityNo"];
        echo "yourChar:".$yourChar;
    } 
function labelPlayerBtn($buttonNo){
    global $datas;
    global $yourChar;
    global $username;
    if (isset($datas[$buttonNo]["Username"])){
        $playerUsername = $datas[$buttonNo]["Username"];
        echo $playerUsername;
        $playerChar = $datas[$buttonNo]["IdentityNo"];

        if ($playerChar == "merlin"){
            if ($yourChar == "merlin" or $yourChar == "mushroom"){
                echo "(merlin)";
            } else {
                echo "(?)";
            }
        }

        if ($playerChar == "mushroom"){
            if ($yourChar == "mushroom"){
                echo "(mushroom)";
            } else {
                echo "(?)";
            }
        }

        if ($playerChar == "villager"){
            if ($playerUsername == $username){
                echo "(villager)";  
            } else {
                echo "(?)";
            }
            
        }
        if ($playerChar == "minion"){
            if ($yourChar == "minion" or $yourChar == "assassin" or $yourChar == "merlin"){
                echo "(minion)";
            } else {
                echo "(?)";
            }
        }

        if ($playerChar == "assassin"){
            if ($yourChar == "minion" or $yourChar == "assassin" or $yourChar == "merlin"){
                echo "(assassin)";
            } else {
                echo "(?)";
            }
        }

    } else {
        echo "";
    }
}
?>



<br>

<div class="main-div">

    <div class="header">

        <h1>Avalon</h1>
        <!-- Game ID label-->
        <label id="gameIDLabel"><?php

        if (isset($gameID)){
            echo "Game ID: ".$gameID."<br>";
        } else {
            echo "Game ID: <br>";
        }

        ?></label>

        <!-- Username label-->
        <label id="usernameLabel"><?php
        if (isset($username)){
            echo "Username: ".$username."<br>";
        } else {
            echo "Username: <br><br>";
        }
        ?></label>

        <form action="createPage.php">
            <button class="button" id="createBtn">Create GameID</button>
        </form>

        <form action="joinPage.php">
            <button class="button" id="enterBtn">Enter GameID</button>
        </form>
        
        <form method="post">
            <button class="button" type="submit" 
            name="logOutBtn" id="logOutBtnID">Log Out </button>
        </form>
    </div>

    <div class="player-div">

        <button id="playerLbl">Players:</button>

        <button id="player0"><?php
        labelPlayerBtn(0);
        ?></button>

        <button id="player1"><?php
        labelPlayerBtn(1);
        ?></button>

        <button id="player2"><?php
        labelPlayerBtn(2);
        ?></button>

        <button id="player3"><?php
        labelPlayerBtn(3);
        ?></button>

        <button id="player4"><?php
        labelPlayerBtn(4);
        ?></button>

        <button id="player5"><?php
        labelPlayerBtn(5);
        ?></button>

        <button id="player6"><?php
        labelPlayerBtn(6);
        ?></button>

        <button id="player7"><?php
        labelPlayerBtn(7);
        ?></button>

        <button id="player8"><?php
        labelPlayerBtn(8);
        ?></button>

        <button id="player9"><?php
        labelPlayerBtn(9);
        ?></button>

        <div class="player-btm-div">
            <form action="newGame.php">
                <button id="newGameBtn" class="button">New Game</button>
            </form>
            <form action="resultPage.php">
                <button id="seeResultBtn" class="button">See Results</button>
            </form>

            <button type="button" id="voteBtn" class="button">VOTE</button>
            <label id="voteLabel"><?php echo "Votes: ".$noOfVotes; ?></label>
            

            
        </div>

    </div>

    <div class="map-div">
        <div class="mission-div" id="mission-one">
            <p class="mission-info" id="mission-info-1"></p>
            <button id="button-1"><?php
                echo $arrayUsing[0];
            ?></button>
        </div>
        <div class="mission-div" id="mission-two">
            <p class="mission-info" id="mission-info-2"></p>
            <button id="button-2"><?php
                echo $arrayUsing[1];
            ?></button>
        </div>
        <div class="mission-div" id="mission-three">
            <p class="mission-info" id="mission-info-3"></p>
            <button id="button-3"><?php
                echo $arrayUsing[2];
            ?></button>
        </div>
        <div class="mission-div" id="mission-four">
            <p class="mission-info" id="mission-info-4"></p>
            <button id="button-4"><?php
                echo $arrayUsing[3];
            ?></button>
        </div>
        <div class="mission-div" id="mission-five">
            <p class="mission-info" id="mission-info-5"></p>
            <button id="button-5"><?php
                echo $arrayUsing[4];
            ?></button>
        </div>
        <div class="mission-div" id="info-editor">
            <input id="missionToEdit" placeholder="Mission No:" type="number">
            <input id="missionPlayers" placeholder="Players:" type="text">
            <input id="missionResult" placeholder="Result:" type="text">
            <button id="go-btn">Go</button>
        </div>
    </div>



</div>



<?php
//Set $logOutIsVisible to false as default 
//if $_SESSION['logOutIsVisible'] hasn't been set
if (isset($_SESSION['logOutIsVisible'])){
    $logOutIsVisible = $_SESSION['logOutIsVisible']; 
} else {
    $logOutIsVisible = false;
}
?>


<script src="https://code.jquery.com/jquery-3.5.1.min.js" 
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" 
        crossorigin="anonymous">
</script>

<script type="text/javascript">
    $(document).ready(function() { 
        console.log("ready");
        // When button with id 'login' is clicked
        $("#go-btn").on('click', function(){
            //Get input stuff with id #email
            var missionToEdit= $("#missionToEdit").val();
            var missionPlayers= $("#missionPlayers").val();
            var missionResult= $("#missionResult").val();
            var missionToEditInt = parseInt(missionToEdit);

            var gameID = "<?php echo $gameID?>";

            //if empty fields
            if (missionToEdit == ""){
                alert('Empty Field(s)! Check your inputs.');

            } else if (gameID == "not yet set"){
                alert('Game ID has not been set');
            } else if ((missionToEdit < 1) || (missionToEdit > 5)) {
                alert('Mission Number must be 1-5');
            } else if (!(Number.isInteger(missionToEditInt))){
                alert('Mission Number must be an interger');
            } else {
                $.ajax({
                        url: 'editMissionInfo.php',
                        method: 'POST',
                        dataType: 'text',
                        data: {
                            mission: 1,
                            missionToEdit: missionToEdit,
                            missionPlayers: missionPlayers,
                            missionResult: missionResult,
                            gameID: gameID
                        }
                        
                }).done(function(returnedData){
                    location.reload();
                })
            }

        })
    });
</script>



<script type="text/javascript">
    //document.ready means the page is ready
        //meaning all elements of the html page is loaded
        $(document).ready(function() { 
            console.log("ready again");
            //Show or hide log out button and createGame and joinPage button
            
            logOutIsVisible = "<?php echo $logOutIsVisible?>";

            if (logOutIsVisible == false){
                //log Out button html HERE
                // new game btn, votes label, vote btn, see results btn 
                document.getElementById("logOutBtnID").style.display = "none";
                document.getElementById("newGameBtn").style.display = "none";
                document.getElementById("voteLabel").style.display = "none";
                document.getElementById("voteBtn").style.display = "none";
                document.getElementById("seeResultBtn").style.display = "none";

            } else {
                //echo createGame and joinGame button
                document.getElementById("createBtn").style.display = "none";
                document.getElementById("enterBtn").style.display = "none";
            }


            // When button with id 'login' is clicked
            $("#voteBtn").on('click', function(){
                //Get input stuff with id #email
                var JSgameID = "<?php echo $gameID?>";
                var JSusername= "<?php echo $username?>";

                //if empty fields
                if (JSgameID == "" || JSusername == ""){
                    alert('Empty Field(s)! Check your inputs.');
                } else {
                    $.ajax({
                            url: 'checkIfVoted.php',
                            method: 'POST',
                            dataType: 'text',
                            data: {
                                vote: 1,
                                PHPgameID: JSgameID, 
                                PHPusername: JSusername
                            }
                            
                    }).done(function(returnedData){
                        console.log(returnedData);
                        if(returnedData == "not voted yet, can vote now"){
                            //alert(returnedData);
                            window.location.href="votePage.php"; 
                        } else {
                            alert(returnedData);
                        }
                    })
                }

            })
        });

    
</script>

<script type="text/javascript">

$(document).ready(function() { 
        console.log("ready as");
        var playersInEveryMission = <?php echo json_encode($playersInEveryMission); ?>;
        var resultsInEveryMission = <?php echo json_encode($resultsInEveryMission); ?>;
        console.log(playersInEveryMission);
        console.log(resultsInEveryMission);

        for (i = 0; i < 5; i++) { 
            if (playersInEveryMission[i] == null) {
                playersInEveryMission[i] = "";
            }
            
        
        }
        document.getElementById("mission-info-1").textContent = "Mission Players: " + playersInEveryMission[0];
        document.getElementById("mission-info-2").textContent = "Mission Players: " + playersInEveryMission[1];
        document.getElementById("mission-info-3").textContent = "Mission Players: " + playersInEveryMission[2];
        document.getElementById("mission-info-4").textContent = "Mission Players: " + playersInEveryMission[3];
        document.getElementById("mission-info-5").textContent = "Mission Players: " + playersInEveryMission[4];;

        for ($x = 0; $x < 5; $x++) {
            var plus1 = $x + 1;
            var buttonID = "button-" + plus1;
            if (resultsInEveryMission[$x] == "success"){
                document.getElementById(buttonID).style.background='rgb(65, 87, 175)';
            } else if (resultsInEveryMission[$x] == "failed"){
                document.getElementById(buttonID).style.background='#F65058FF';
            } else {
                document.getElementById(buttonID).style.background='grey';
            }
        }
        // When button with id 'login' is clicked
    });

</script>



<script>

function safeToString(x) {
  switch (typeof x) {
    case 'object':
      return 'object';
    case 'function':
      return 'function';
    default:
      return x + '';
  }
}


//Refresh page code

var JSgameID = "<?php echo $gameID?>";
var JSusername= "<?php echo $username?>";

window.setInterval(function(){
    $.ajax({
        url: 'refresh.php',
        method: 'POST',
        dataType: 'text',
        data: {
            refresh: 1,
            PHPgameID: JSgameID, 
            PHPusername: JSusername
        }
            
    }).done(function(returnedData){
        //alert(returnedData);
        

        var fields = returnedData.split("||");
        var playersString = fields[0];
        var charsString = fields[1];
        var yourCharString = fields[2];
        var missionPlayersString = fields[3];
        var missionResultString = fields[4];
        
        
        var playersArray = playersString.split("//");
        var charsArray = charsString.split("//");

        var missionPlayersArray = missionPlayersString.split("//");
        missionPlayersArray.pop(); //Delete last element because it's extra
        var missionResultArray = missionResultString.split("//");
        missionResultArray.pop(); //Delete last element because it's extra, 
        // Trust yourself Marco !!!!

        console.log(missionResultArray);

        //alert(yourCharString);

    //The first element of playersArray is the no. of votes
        var noOfVotes= "Votes: ".concat(playersArray[0]);
        document.getElementById('voteLabel').innerHTML = noOfVotes;

        var i;
        console.log(playersArray.length);
        for (i = 1; i < (12 - 1); i++) { 
            var player = playersArray[i];
            var character = charsArray[i-1];

            if (player == undefined) {
                player = "";
            } 
            if ((character == undefined) || (character == "")) {
                character = "";
            } else {
                character = character;
            }

            

            if (character == "merlin"){
                if ((yourCharString == "merlin") || (yourCharString == "mushroom")){
                    character = "(merlin)";
                } else {
                    character = "(?)";
                } 
            }
            if (character == "mushroom"){
                if (yourCharString == "mushroom"){
                    character = "(mushroom)";
                } else {
                    character = "(?)";
                } 
            }

            if (character == "villager"){
                if (player == "<?php echo $username?>"){
                    character = "(villager)";
                } else {
                    character = "(?)";
                } 
            }

            if (character == "minion"){
                if ((yourCharString == "minion") || (yourCharString == "assassin") || (yourCharString == "merlin")){
                    character = "(minion)";
                } else {
                    character = "(?)";
                } 
            }

            if (character == "assassin"){
                if ((yourCharString == "minion") || (yourCharString == "assassin") || (yourCharString == "merlin")){
                    character = "(assassin)";
                } else {
                    character = "(?)";
                } 
            }

            var playerLabelID = "player" + (i - 1);
            document.getElementById(playerLabelID).innerHTML = player + character;
        }

        //Refresh map div

        for (i = 0; i < 5; i++) { 
            var missionPlayers = missionPlayersArray[i];
            var missionLabelID = "mission-info-" + (i + 1);
            document.getElementById(missionLabelID).innerHTML = "Mission Players: " + missionPlayers;

            var missionResult = missionResultArray[i];
            var missionButtonID = "button-" + (i + 1);

            if (missionResult == "success") {
                document.getElementById(missionButtonID).style.background='rgb(65, 87, 175)';
            } else if (missionResult == "failed") {
                document.getElementById(missionButtonID).style.background='#F65058FF';
            } else {
                document.getElementById(missionButtonID).style.background='grey';
            }
            


        }
        


    })


}, 5000);


</script>




</body>
</html>



