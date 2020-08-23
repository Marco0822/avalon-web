<?
 require_once('connectDB.php');

session_start();

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

$no = "no";
$sql = "UPDATE Players SET IsVoted=? WHERE gameID=?";
$stmt = $conn->prepare($sql); 
$stmt->bind_param("ss", $no, $gameID);
$stmt->execute();

header("location:../resultPage.php");
exit();

