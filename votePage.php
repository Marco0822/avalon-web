<?php
        $agreeOrNot = "don't know if agree";

        session_start();
        $gameID = $_SESSION['gameID'];
        $username = $_SESSION['uid'];
        
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
        }
        function voteToDB() { 
            global $agreeOrNot;
            global $gameID;
            global $username;
            $conn = new mysqli("localhost", "root", "", "avalonApp");
            if ($conn->connect_errno) {
                echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
            }

            if (!($stmt = $conn->prepare("INSERT INTO Players(gameID, Username) VALUES (?, ?)"))) {
                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
            }
            
            if (!$stmt->bind_param("ss", $agreeOrNot, $username)) {
                echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            }
            
            if (!$stmt->execute()) {
                echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            }

            echo "Inserted ".$agreeOrNot;
            
            $stmt->close();
            header("location:index.php");
            exit();
        } 
    ?> 
  
    <form method="post"> 
        <input type="submit" name="agree"
                class="button" value="AGREE" /> 
          
        <input type="submit" name="disagree"
                class="button" value="DISAGREE" /> 
    </form> 