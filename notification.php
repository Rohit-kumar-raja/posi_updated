<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("location:index.php");
} else {
    include "./database/connection.php";
    include "./database/getMsgNotif.php";

    // include_once("database/connection.php");  
    // include("database/getMsgNotif.php");   
    include "posi_header.php";
?>

    <!--// html code-->


    <!DOCTYPE html>
    <html>

    <head>

        <title>Posigraph </title>
        <link rel="icon" type="image/x-icon" href="https://posigraph.com/posi_favicon.png">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style/w3.css">
        <link rel="stylesheet" href="style/w3-theme-blue-grey.css">
        <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans'>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    </head>

    <body class="w3-theme-l5">
        <div class="mt-5 pt-5 p-5">
            <?php
            $id = $_SESSION['id'];
            $notification_all_users = fetchResult('notifications', 'notificationFor=' . $id . ' ORDER BY `notificationId` DESC');
            while ($row = mysqli_fetch_array($notification_all_users)) {
                $post_idd = $row['postId'];
                $users_by_data = fetchRow('user', '`userId` = ' . $row['notificationBy'] . '');
                $my_img = $users_by_data["dp"];
                $post_data = fetchRow('posts', 'postId=' . $row['postId'] . '');
            ?>
                <div onclick="seeNotification(<?= $row['notificationId'] ?>)" class='alert alert-<?= $row['notificationStatus'] == 'new' ? 'success' : 'secondary' ?>  show rounded-pill'>
                    <a onclick="seeNotification(<?= $row['notificationId'] ?>)" class="text-primary" href='home.php?id=<?= $users_by_data['userId'] ?>'> <img width="50px" class="rounded-circle" src='dp/<?= $my_img ?>' /></a>
                    <a onclick="seeNotification(<?= $row['notificationId'] ?>)" href='home.php?id=<?= $users_by_data['userId'] ?>' class='w3-bar-item w3-button '><?= $row['notificationMessage']==''?'go to post':$row['notificationMessage'] ?></a> - 
                    <a onclick="seeNotification(<?= $row['notificationId'] ?>)" class="text-primary" href="dashboard.php?post_id=<?= $row['postId'] ?>&post_type=<?= $row['post_type'] ?>"><?= $post_data['postContent'] == '' ? '' : $post_data['postContent'] ?></a>
                </div>
            <?php } ?>
        </div>
    </body>

    </html>

<?php

}
?>

<script>
    function seeNotification(id) {
        $.ajax({
            method: 'post',
            url: "database/seenotification.php",
            data:{id:id},
            success: function(result) {

            }
        });
    }
</script>