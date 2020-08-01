<?php
$conn = new mysqli("localhost", "root", "", "avalonApp");
if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
}

$username = $_POST['uid'];
$gameID = $_POST['game-ID'];

echo $username;
echo '<br>';
echo $gameID;

session_start();
$_SESSION['gameID'] = $gameID;
$_SESSION['uid'] = $username;


$sql = "SELECT * FROM Players WHERE gameID=?"; // SQL with parameters
$stmt = $conn->prepare($sql); 
$stmt->bind_param("s", $gameID);
$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result
$row = $result->fetch_assoc(); // fetch data   

if (mysqli_num_rows($result) !== 0) { //If gameID is taken
    header("location:../createPage.php?error=gameIDTaken");
    exit();
} else {
    if (!($stmt = $conn->prepare("INSERT INTO Players(gameID, Username) VALUES (?, ?)"))) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
    }
    
    
    if (!$stmt->bind_param("ss", $gameID, $username)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    //Successfully entered gameId and username to database
    $stmt->close();
    header("location:../index.php?gameID=".$gameID."&uid=".$username);
    exit();
}

