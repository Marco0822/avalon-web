<?php




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
    <title>Create Game Page</title>
</head>
<body>

<form method="post">
    <input id="game-ID" type="text" placeholder="Game ID:"><br>
    <input id="uid" type="text" placeholder="Username:"><br>
    <button type="button" id="createGameBtn">Create Game</button>
    
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
            $("#createGameBtn").on('click', function(){
                //Get input stuff with id #email
                var JSgameID = $("#game-ID").val();
                var JSusername= $("#uid").val();

                //if empty fields
                if (JSgameID == "" || JSusername == ""){
                    alert('Empty Field(s)! Check your inputs.');
                } else {
                    $.ajax({
                            url: 'createGamePHP.php',
                            method: 'POST',
                            dataType: 'text',
                            data: {
                                createGame: 1,
                                PHPgameID: JSgameID, 
                                PHPusername: JSusername
                            }
                            
                    }).done(function(returnedData){
                        console.log(returnedData);
                        if(returnedData == "Inserted data successfully!"){
                            //alert("Inserted data successfully!");
                            window.location.href="index.php"; 
                        } else {
                            alert(returnedData);
                        }
                    })
                }

            })
        });
    
</script>

<p id="response">adsds</p>

</body>
</html>