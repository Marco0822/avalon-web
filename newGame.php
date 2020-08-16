<?php
    /*//Not allow user to start new game if no gameID
    if (!isset($_SESSION['gameID'])){
        header("Location:index.php?error=cannotStartGame");
        exit();
    }*/
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

<h2 id="demo">Testing</h2>
<h2 id="shuffle">Shuffled</h2>

<input type="number" placeholder="Mushroom Number" id="mushroomNo">
<input type="number" placeholder="Merlin Number" id="merlinNo">
<input type="number" placeholder="Villager Number" id="villagerNo">
<input type="number" placeholder="Minion Number" id="minionNo">
<input type="number" placeholder="Assassin Number" id="assassinNo">

<br><br>

<button id="newArrayBtn">Make New Array</button>
<button onclick="backToIndex()">Back</button>
