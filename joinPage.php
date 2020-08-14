<?php
//If Create Game button is pressed
if (isset($_POST['createGame'])){

    require_once('phpstuff/connectDB.php');

    //get username and gameId from input
    $username = $_POST['PHPusername'];
    $gameID = $_POST['PHPgameID'];

    //Check if desired gameID name is taken
    $sql = "SELECT * FROM Players WHERE gameID=?"; 
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("s", $gameID);
    $stmt->execute();
    $result = $stmt->get_result();  

    //If gameID not set
    if (mysqli_num_rows($result) == 0) { 
        exit("Game ID has not been set!");
        
    //Else try to insert gameID and username into table Players
    } else {

        //Check if desired gameID name is taken
        $sql = "SELECT * FROM Players WHERE gameID=? AND username=?"; 
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("ss", $gameID, $username);
        $stmt->execute();
        $result = $stmt->get_result(); 

        if (mysqli_num_rows($result) !== 0) { 
            exit("Username has been taken!");
        } 
    
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

/*

if(array_key_exists('createGameFunction',$_POST)){

        //Successfully entered gameId and username to database
        session_start();

        //store gameID and usernameID as global variables
        $_SESSION['gameID'] = $gameID;
        $_SESSION['uid'] = $username;

        //log Out Btn should be visible after created game successfully
        $_SESSION['logOutIsVisible'] = true;

        $stmt->close();
        header("location:index.php");
        exit();
}*/
 


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
    <title>Join Game Page</title>
</head>
<body>

<form method="post">
    <input id="game-ID" type="text" placeholder="Game ID:"><br>
    <input id="uid" type="text" placeholder="Username:"><br>
    <button type="button" id="joinGameBtn">Join Game</button>
    
    <!--back button-->
    <input type="submit" name="backBtn"
    class="button" value="Back"/> 
</form>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" 
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" 
        crossorigin="anonymous">
</script>

<script type="text/javascript">
    //document.ready means the page is ready
        //meaning all elements of the html page is loaded
        $(document).ready(function() {  
                // When button with id 'login' is clicked
                $("#joinGameBtn").on('click', function(){
                    //Get input stuff with id #email
                    var JSgameID = $("#game-ID").val();
                    var JSusername= $("#uid").val();

                    //if empty fields
                    if (JSgameID == "" || JSusername == ""){
                        alert('Empty Field(s)! Check your inputs.');
                    } else {
                        $.ajax(
                            {
                                url: 'joinPage.php',
                                method: 'POST',
                                data: {
                                    createGame: 1,
                                    PHPgameID: JSgameID, 
                                    PHPusername: JSusername
                                },
                                success: function(response) {
                                    $("#response").html(response);

                                    if (response == "Game ID has not been set!"){
                                        alert("Game ID has not been set!");

                                    } else if (response == "Username has been taken!"){
                                        alert("Username has been taken!"); 

                                    } else if (response == "Inserted data successfully!"){
                                        window.location.href="index.php"; 
                                    }
                                },
                                dataType: 'text'
                            }
                        );
                    }

                })
            });
    
</script>

<p id="response">adsds</p>

</body>
</html>