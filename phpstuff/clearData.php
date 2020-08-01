<?
 $conn = new mysqli("localhost", "root", "", "avalonApp");
 if ($conn->connect_errno) {
     echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
 }

 $sql = "DELETE FROM Players WHERE gameID='agree' OR gameID='disagree'";

 if ($conn->query($sql) === TRUE) {
   echo "Record deleted successfully";
 } else {
   echo "Error deleting record: " . $conn->error;
 }
 
 $conn->close();
 header("location:../resultPage.php");
exit();


