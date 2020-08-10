<?
 require_once('connectDB.php');

 $sql = "DELETE FROM Players WHERE gameID='agree' OR gameID='disagree'";

 if ($conn->query($sql) === TRUE) {
   echo "Record deleted successfully";
 } else {
   echo "Error deleting record: " . $conn->error;
 }
 
 $conn->close();
 header("location:../resultPage.php");
exit();


