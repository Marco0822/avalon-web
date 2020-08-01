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
 

    if(isset($_GET['gameID'])) { 
        $gameID = $_GET['gameID'];
        echo '<br>';
        echo "Game ID: ";
        echo $gameID;
        echo '<br>';
        
        if(isset($_GET['uid'])) { 
            $username = $_GET['uid'];
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

?>

<br>
<br>
<form action="votePage.php">
    <button>Vote</button>
</form>

<form action="resultPage.php">
    <button>See Results</button>
</form>


</body>
</html>