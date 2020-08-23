<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Just Avalon</title>
    <link rel="stylesheet" href="styles/resultPageStyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@500;700&display=swap" rel="stylesheet">
</head>

<div class="body-div">

    <p class="header-p">Results :O</p>
    <p id="results_paragraph">

    <?php
    require_once('phpstuff/connectDB.php');

    session_start();

    //Echo global variable gameID
    if(isset($_SESSION['gameID'])) {  
        $gameID = $_SESSION['gameID'];
    }
        
    //Echo global variable uid
        if(isset($_SESSION['uid'])) {  
            $username = $_SESSION['uid'];
        }

    $agree = "agree";
    $sql = "SELECT * FROM Players WHERE IsVoted=? AND gameID=?"; // SQL with parameters
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("ss", $agree, $gameID);
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

    $datas = [];  //Empty array before use again
    $agree = "agree_hide";
    $sql = "SELECT * FROM Players WHERE IsVoted=? AND gameID=?"; // SQL with parameters
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("ss", $agree, $gameID);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result

    if (mysqli_num_rows($result) > 0){ //There's data in the database
        while ($row = mysqli_fetch_assoc($result)){ //still havin rows to fetch
            $datas[] = $row;
        }
    }

    foreach($datas as $data){
        echo "<br>";
        echo "unknown";
    }

    $disagree = "disagree";
    $sql = "SELECT * FROM Players WHERE IsVoted=? AND gameID=?"; // SQL with parameters
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("ss", $disagree, $gameID);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result

    $datas = []; //Empty array before use

    if (mysqli_num_rows($result) > 0){ //There's data in the database
        while ($row = mysqli_fetch_assoc($result)){ //still havin rows to fetch
            $datas[] = $row;
        }
    }
    echo "<br><br>";
    echo "Players who disagreed:";

    foreach($datas as $data){
        echo "<br>";
        echo $data["Username"];
    }

    $datas = [];  //Empty array before use again
    $disagree = "disagree_hide";
    $sql = "SELECT * FROM Players WHERE IsVoted=? AND gameID=?"; // SQL with parameters
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("ss", $disagree, $gameID);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result

    if (mysqli_num_rows($result) > 0){ //There's data in the database
        while ($row = mysqli_fetch_assoc($result)){ //still havin rows to fetch
            $datas[] = $row;
        }
    }

    foreach($datas as $data){

        

        echo "<br>";
        echo "unknown";
    }
    echo "<br>";
    echo "<br>";

    ?>

    </p>

    <div class="btn-div">
        <form action="phpstuff/clearData.php">
            <button class="button">Clear Data</button>
        </form>

        <form action="phpstuff/backToIndex.php">
            <button class="button">Back</button>
        </form>
    </div>

</div>








