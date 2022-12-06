<?php
session_start();
include "database/connection.php";
session_destroy();
header("location:index.php");

// $upd = "update user set logInStatus='Offline' where userId=".$_SESSION['id'];
// if(mysqli_query($conn,$upd))
// {
//      session_destroy();
//     header("location:index.php");
// }
// else
//     echo mysqli_error($conn);
?>