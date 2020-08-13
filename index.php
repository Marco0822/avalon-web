<?php
    
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

        //There's data in the database
        if (mysqli_num_rows($result) > 0){ 

            //still havin rows to fetch
            while ($row = mysqli_fetch_assoc($result)){ 
                $datas[] = $row;
            }
        }
        echo "<br>";
        echo "<br>";
        echo "Players: ";
        echo "<br>";

        foreach($datas as $data){
            echo "<br>";
            echo $data["Username"];
        }
    }

    // Check if there's any votes
    require_once('phpstuff/connectDB.php'); 
    $agree = "agree";
    $disagree = "disagree";
    $sql = "SELECT * FROM Players WHERE gameID=? OR gameID=?"; 
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("ss", $agree, $disagree);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result

    if (mysqli_num_rows($result) > 0){ //There's data in the database
        $noOfVotes = mysqli_num_rows($result);
    } else {
        $noOfVotes = "No votes yet!";
    }



    //if vote btn is pressed
    if(array_key_exists('voteBtn', $_POST)) { 
        require_once('phpstuff/connectDB.php');
    
       // Check if user has gameID
        if ($gameID == "not yet set") {
            global $error;
            $error = "Unable to vote, create or enter game first!";
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
                $error = "votedAlready";
                echoAlert("votedAlready");
                
            //Else, go to vote page 
            } else {
                header("location:votePage.php");
                exit();
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

?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Avalon Web</title>
</head>
<body>

    
<br>
<br>

 <!-- Error Message Label-->
<label id="errorMessageLabel"><?php 
    echo "Error Message here: ".$error."<br>";
?></label>

<br>

<!-- Game ID label-->
<label id="gameIDLabel"><?php

if (isset($gameID)){
    echo "Game ID: ".$gameID."<br>";
} else {
    echo "Game ID: <br>";
}

?></label>

<!-- Username label-->
<label><?php
if (isset($username)){
    echo "Username: ".$username."<br>";
} else {
    echo "Username: <br><br>";
}

?></label>




<?php


//Set $logOutIsVisible to false as default 
//if $_SESSION['logOutIsVisible'] hasn't been set
if (isset($_SESSION['logOutIsVisible'])){
    $logOutIsVisible = $_SESSION['logOutIsVisible']; 
} else {
    $logOutIsVisible = false;
   
}


//If log out button should be visible, for example if user is in game, 
//make it so

if (!($logOutIsVisible==false)){

    //log Out button html HERE
    // new game btn, votes label, vote btn, see results btn 
    echo '
    <form method="post">
    <input type="submit" id="logOutBtnID" name="logOutBtn"
    class="button" value="Log Out"/> 
    </form>

    <form action="newGame.php">
    <button>New Game</button>
    </form>


    <label><?php echo "Votes: ".$noOfVotes; ?></label>

    <form method="post"> 
        <input type="submit" name="voteBtn"
        class="button" value="VOTE" /> 
    </form> 

    <form action="resultPage.php">
        <button>See Results</button>
    </form>
    
    
    ';
} else {

    // Log out button should be invisible, meaning create and enter button 
    // should be visible

    //create Game Button and join game button 
    echo '
    
    <div id="header">

    <form action="createPage.php">
        <button>Create GameID</button>
    </form>

    <form action="joinPage.php">
        <button>Enter GameID</button>
    </form>

    </div>
    
    ';
}

 
?>


</body>
</html>



