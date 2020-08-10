<?php
require_once('phpstuff/connectDB.php');

$agree = "agree";
$sql = "SELECT * FROM Players WHERE gameID=?"; // SQL with parameters
$stmt = $conn->prepare($sql); 
$stmt->bind_param("s", $agree);
$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result

$datas = array();

if (mysqli_num_rows($result) > 0){ //There's data in the database
    while ($row = mysqli_fetch_assoc($result)){ //still havin rows to fetch
        $datas[] = $row;
    }
}
echo "<br>";
echo "Players who agreed:";

foreach($datas as $data){
    echo "<br>";
    echo $data["Username"];
}

$disagree = "disagree";
$sql = "SELECT * FROM Players WHERE gameID=?"; // SQL with parameters
$stmt = $conn->prepare($sql); 
$stmt->bind_param("s", $disagree);
$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result

$datas = array();

if (mysqli_num_rows($result) > 0){ //There's data in the database
    while ($row = mysqli_fetch_assoc($result)){ //still havin rows to fetch
        $datas[] = $row;
    }
}
echo "<br>";
echo "Players who disagreed:";

foreach($datas as $data){
    echo "<br>";
    echo $data["Username"];
}
?>

<form action="phpstuff/clearData.php">
    <button>clearData</button>
</form>

<form action="phpstuff/backToIndex.php">
    <button>Back</button>
</form>




