<?php
include("connection.php");

// mysqli_select_db($conn,"posigraph_socialplexus");
function image($userId)
{
  global $conn;
  // echo "select * from  posts where userId={$_SESSION['id']}  AND type LIKE 'image%' ORDER BY post#pop-up-divDate DESC";
  // exit;
  $query = "select * from  posts where userId={$userId}  AND type LIKE 'image%' ORDER BY postDate DESC";
  $image = mysqli_query($conn, $query);
  while ($row = mysqli_fetch_array($image)) {
    $img_id =  $row['postId'];
?>
    <!-- <a class="centered" href=""> <i class="fas fa-trash-alt"></i></a> -->
    <img src='imagePost/<?= $row['postImage'] ?>' class='<?= $row['postId'] ?>'>

<?php
  }
}


// echo "<div class='col-4'>
//                  <img src='imagePost/{$row['postImage']}' style='width:100%; margin-bottom:20px;' class='w3-margin-bottom'>
//                </div>";
?>