<?php
    
    session_start();

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
            header("location:index.php?error=votedAlready");
            exit();
        //Else, go to vote page 
        } else {
            header("location:votePage.php");
            exit();
        }
       
    } 


    //If log out button is pressed
    if(array_key_exists('logOutBtn', $_POST)) { 

    //Delete your own username from the database
    $sql = "DELETE FROM Players WHERE gameID='$gameID' AND Username='$username'";
    
     if ($conn->query($sql) === TRUE) {
       echo "Record deleted successfully";
       $_SESSION['gameID'] = "null";
       $_SESSION['uid'] = "null";
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

<!-- Game ID label-->
<label><?php
echo "Game ID: ".$gameID."<br>";
?></label>

<!-- Username label-->
<label><?php
echo "Username: ".$username."<br>";
?></label>



<form method="post"> 
    <input type="submit" name="voteBtn"
    class="button" value="VOTE" /> 
</form> 

<label><?php echo "Votes: ".$noOfVotes; ?></label>

<form action="resultPage.php">
    <button>See Results</button>
</form>



<?php

$logOutIsVisible = $_SESSION['logOutIsVisible']; 

//If log out button should be visible, for example if user is in game, 
//make it so
if (!($logOutIsVisible==false)){

    //log Out button html HERE!!
    echo '<form method="post">
    <input type="submit" id="logOutBtnID" name="logOutBtn"
    class="button" value="Log Out"/> 
    </form>';
} else {

    // Log out button should be invisible, meaning create and enter button 
    // should be visible

    //create Game Button and join game button HERE
    echo '<div id="header">

    <form action="createPage.php">
        <button>Create GameID</button>
    </form>

    <form action="joinPage.php">
        <button>Enter GameID</button>
    </form>

    </div>';
}

    
?>


</body>
</html>


