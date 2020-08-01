<?php
$conn = new mysqli("localhost", "root", "", "avalonApp");
if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
}


session_start();
$gameID = $_SESSION['gameID'];
$username = $_SESSION['username'];


if (!($stmt = $conn->prepare("INSERT INTO Players(gameID, Username) VALUES (?, ?)"))) {
    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
}

$disagree = "disagree";
if (!$stmt->bind_param("ss", $disagree, $username)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}

if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}

$stmt->close();
    header("location:../index.php?gameID=".$gameID."&uid=".$username);
    exit();
