<?php
include "../framwork/main.php";
$notify=array('notificationStatus'=>'old');
$id=$_POST['id'];
 updateAll('notifications',$notify,' notificationId='.$id.'');
