<?php
// session_start();
include("connection.php");
$database="posigraph_socialplexus";
mysqli_select_db($conn,$database);

$p1 = $_GET['pid1'];
$type = $_GET['type'];
$p2 = $_GET['pid2'];

// $query = "update `battle` set player1_post='' WHERE player1_id=$p1 && player2_id=$p2";
$query = "DELETE FROM `battle` WHERE player1_id=$p1 && player2_id=$p2";
$done = mysqli_query($conn, $query);
if($done){
    echo '<script>
    location.replace("../dashboard.php")
    </script>';
}
?>