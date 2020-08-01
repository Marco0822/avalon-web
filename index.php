<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avalon Web</title>
</head>
<body>
<!--
    <div id="p1">Testing</div>
    <div id="p2">Testing</div>
    <div id="p3">Testing</div>
    <div id="p4">Testing</div>
    <div id="p5">Testing</div>
    <div id="p6">Testing</div>
    <div id="p7">Testing</div>
    <div id="p8">Testing</div>
    <div id="p9">Testing</div>
    <div id="p10">Testing</div>
    <br>
    -->
    
<form action="createPage.php">
    <button>Create GameID</button>
</form>

<form action="joinPage.php">
    <button>Enter GameID</button>
</form>



<?php
    
    session_start();

    if(isset($_SESSION['gameID'])) { 
        $gameID = $_SESSION['gameID'];
        echo '<br>';
        echo "Game ID: ";
        echo $gameID;
        echo '<br>';
        
        if(isset($_SESSION['uid'])) { 
            $username = $_SESSION['uid'];
            echo "Your username: ";
            echo $username;
            echo '<br>';
        }

        $conn = new mysqli("localhost", "root", "", "avalonApp");
        if ($conn->connect_errno) {
            echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
        }




        $sql = "SELECT * FROM Players WHERE gameID=?"; // SQL with parameters
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("s", $gameID);
        $stmt->execute();
        $result = $stmt->get_result(); // get the mysqli result
    

        $datas = array();

        if (mysqli_num_rows($result) > 0){ //There's data in the database
            while ($row = mysqli_fetch_assoc($result)){ //still havin rows to fetch
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

        //print_r($datas);

        /*
        $row = $result->fetch_assoc(); // fetch data   
        echo "<br>Echoed Stuff: ";
        echo $row["Username"];
        */
    }

    $agree = "agree";
    $disagree = "disagree";
    $sql = "SELECT * FROM Players WHERE gameID=? OR gameID=?"; // SQL with parameters
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("ss", $agree, $disagree);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result

    if (mysqli_num_rows($result) > 0){ //There's data in the database
        $noOfVotes = mysqli_num_rows($result);
    } else {
        $noOfVotes = "No votes yet!";
    }

    if(array_key_exists('voteBtn', $_POST)) { //if vote btn is pressed
        $conn = new mysqli("localhost", "root", "", "avalonApp");
        if ($conn->connect_errno) {
            echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
        }

        global $gameID;
        global $username;

        $agree = "agree";
        $disagree ="disagree";
        $sql = "SELECT * FROM Players WHERE (gameID=? OR gameID=?) AND Username=?"; // SQL with parameters
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("sss", $agree, $disagree, $username);
        $stmt->execute();
        $result = $stmt->get_result(); // get the mysqli result
        $row = $result->fetch_assoc(); // fetch data   

        if (mysqli_num_rows($result) !== 0) { //If already voted
            header("location:index.php?error=votedAlready");
            exit();
        } 
        header("location:votePage.php");
        exit();
    } 

?>

<br>
<br>

<form method="post"> 
    <input type="submit" name="voteBtn"
    class="button" value="VOTE" /> 
</form> 

<label><?php echo "Votes: ".$noOfVotes; ?></label>

<form action="resultPage.php">
    <button>See Results</button>
</form>


</body>
</html>