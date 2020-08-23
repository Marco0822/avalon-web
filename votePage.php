<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Just Avalon</title>
    <link rel="stylesheet" href="styles/votePageStyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@500;700&display=swap" rel="stylesheet">
</head>

<?php
        $agreeOrNot = "don't know if agree";

        session_start();

        require_once("phpstuff/connectDB.php");

        if (isset($_SESSION['gameID'])){
            $gameID = $_SESSION['gameID'];
        } else {
            header("Location:index.php?error=noGameID");
            exit();
        }

        if (isset($_SESSION['uid'])){
            $username = $_SESSION['uid'];
        } else {
            header("Location:index.php?error=noUid");
            exit();
        }


        if(array_key_exists('agree', $_POST)) { 
            checkIfVoted(); //not sure this works :(
            global $agreeOrNot;
            $agreeOrNot = 'agree';
            voteToDB(); 
        } 
        else if(array_key_exists('disagree', $_POST)) { 
            checkIfVoted();
            global $agreeOrNot;
            $agreeOrNot = 'disagree';
            voteToDB();
        } 

        function checkIfVoted() {

            global $gameID;
            global $username;
            global $conn;
            $isVoted = "";

            $agree = "agree";
            $disagree ="disagree";
            $sql = "SELECT IsVoted FROM Players WHERE gameID=? AND Username=?"; // SQL with parameters
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("ss", $gameID, $username);
            $stmt->execute();
            $result = $stmt->get_result(); // get the mysqli result  

            while ($row = $result->fetch_row()) {
                $isVoted = $row[0];
            }

            if ($isVoted == "yes") { //If already voted
                header("location:index.php?error=votedAlready");
                exit();
            } 
        }
            function voteToDB() { 
                global $agreeOrNot;
                global $gameID;
                global $username;
                global $conn;

            if(!(isset($_POST['showName']))){ //If checkbox is not ticked
                $agreeOrNot .= "_hide";
            } 

            $sql = "UPDATE Players SET IsVoted=? WHERE gameID=? AND Username=?";
            $stmt = $conn->prepare($sql); 
            $stmt->bind_param("sss", $agreeOrNot, $gameID, $username);
            $stmt->execute();
            

            echo "<br>".$agreeOrNot;
            echo "<br>".$gameID;
            $stmt->close();
            $conn->close();

            header("Location: index.php");
            exit();

            
            
        } 
    ?> 
  
    <form method="post" class="body"> 

        <div class="btn-div">
            <button type="submit" name="agree" class="button">AGREE</button>
            
            <button type="submit" name="disagree" class="button">DISAGREE</button>
        </div>
        
        <div class="checkbox-div">
            <input type="checkbox" name="showName" id="showName">
            <label for="">Show Name in Vote</label>
        </div>
        
    </form> 