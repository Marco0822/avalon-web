<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Just Avalon</title>
    <link rel="stylesheet" href="styles/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@500;700&display=swap" rel="stylesheet">
</head>

<?php
    //Successfully entered gameId and username to database
    session_start();


    //Echo global variable gameID
    if(isset($_SESSION['gameID'])) {  
        $gameID = $_SESSION['gameID'];
    }
    //Echo global variable uid
    if(isset($_SESSION['uid'])) {  
        $username = $_SESSION['uid'];
    }


?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" 
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" 
        crossorigin="anonymous">
</script>
<script>

$(document).ready(function() {  
    // When button with id 'login' is clicked
    $("#newArrayBtn").on('click', function(){
        console.log("tstig");
        
    var mushroomNo = document.getElementById("mushroomNo").value;
    var merlinNo = document.getElementById("merlinNo").value;
    var villagerNo = document.getElementById("villagerNo").value;
    var minionNo = document.getElementById("minionNo").value;
    var assassinNo = document.getElementById("assassinNo").value;
    

    var characterArray = [];
    
    //Add mushroom to characterArray
    for (i = 0; i < mushroomNo; i++) {
        characterArray.push("mushroom")
    }
    //Add merlin to characterArray
    for (i = 0; i < merlinNo; i++) {
        characterArray.push("merlin")
    }
    //Add villager to characterArray
    for (i = 0; i < villagerNo; i++) {
        characterArray.push("villager")
    }
    //Add minion to characterArray
    for (i = 0; i < minionNo; i++) {
        characterArray.push("minion")
    }
    //Add assassin to characterArray
    for (i = 0; i < assassinNo; i++) {
        characterArray.push("assassin")
    }

    document.getElementById("demo").innerText = characterArray;
    shuffled = characterArray.sort(() => Math.random() - 0.5)
    document.getElementById("shuffle").innerText = shuffled;

    var firstChar = characterArray[0];

    var st = JSON.stringify(characterArray);

    $.ajax({
            url: 'changeChars.php',
            method: 'POST',
            dataType: 'text',
            data: {
                charArray: st
            }
            
    }).done(function(returnedData){
        console.log(returnedData);
        if(returnedData == "not voted yet, can vote now"){
            //alert(returnedData);
            window.location.href="votePage.php"; 
        } else {
            alert(returnedData);
        }
    })
    

    })
});
</script>

<script>
function backToIndex(){
    window.location.href = "index.php";
}

</script>

<div class="header">
<h1>Avalon</h1>

<!-- Game ID label-->
<label id="gameIDLabel"><?php

if (isset($gameID)){
    echo "Game ID: ".$gameID."<br>";
} else {
    echo "Game ID: <br>";
}

?></label>

<!-- Username label-->
<label id="usernameLabel"><?php
if (isset($username)){
    echo "Username: ".$username."<br>";
} else {
    echo "Username: <br><br>";
}
?></label>


</div>

<div class="body-div">

<!-- These 2 h2 tags are hidden by css-->
<h2 id="demo">Testing</h2>
<h2 id="shuffle">Shuffled</h2>


<div class="input-container">
    <input class="inputs" type="number" placeholder="Mushroom Number" id="mushroomNo">
    <input class="inputs" type="number" placeholder="Merlin Number" id="merlinNo">
    <input class="inputs" type="number" placeholder="Villager Number" id="villagerNo">
    <input class="inputs" type="number" placeholder="Minion Number" id="minionNo">
    <input class="inputs" type="number" placeholder="Assassin Number" id="assassinNo">
</div>

<br><br>

<div class="btn-container">

    <button id="newArrayBtn" class="button">Make New Array</button>
    <button onclick="backToIndex()" class="button">Back</button>

</div>




</div>
