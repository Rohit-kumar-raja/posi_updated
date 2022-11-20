<style>
  .image-grid {
    --gap: 2px;
    --num-cols: 4;
    --row-height: 300px;

    box-sizing: border-box;
    padding: var(--gap);

    display: grid;
    grid-template-columns: repeat(var(--num-cols), 1fr);
    grid-auto-rows: var(--row-height);
    gap: var(--gap);
  }

  .image-grid>img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .image-grid-col-2 {
    grid-column: span 2;
  }

  .image-grid-row-2 {
    grid-row: span 2;
  }

  /* Anything udner 1024px */
  @media screen and (max-width: 1024px) {
    .image-grid {
      --num-cols: 2;
      --row-height: 200px;
    }
  }
</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<?php
session_start();

if (!isset($_SESSION['email'])) {
  header("location:index.php");
} else {
  include_once("database/connection.php");
  include_once("database/getPost.php");
  include("database/getMyImagePost.php");
  include("database/getMsgNotif.php");
  include "posi_header.php";

  $userid = $_GET['id'] == '' ? $_SESSION['id'] : $_GET['id'];
  $user = fetchRow('user', '`userId`= ' . $userid . '');
  $userDetail = fetchRow('user_details', '`userId`= ' . $userid . '');


?>
  <style>

  </style>

  <!-- profile section started -->
  <div class="container-fluid container">
    <div class="row ">
      <div class="col-lg-12 profile-section">

        <div>

          <div class="col-md-12">
            <div class="profile-card text-center">

              <img class="img-responsive img-fluid" src="./bg.jpg">

              <div class="profile-info">
                <img class="profile-pic" src="dp/<?php echo $user['dp']; ?>">
              </div>
              <h4 class="mt-4">
                <?php echo $user['firstName'] . ' ' . $user['lastName']; ?> <?php if ($userid == $_SESSION['id']) { ?>
                  <a class="text-info" href="myData.php"><i class="fas fa-edit"></i></a>
                <?php } ?>
              </h4>
              <?php
              $one = $user['userId'];

              $get_following = mysqli_query($conn, "SELECT * FROM `friends` WHERE `userOne`='$one' || `userTwo`='$one'");
              $get_follower = mysqli_query($conn, "select receiverId from friend_request where senderId='$one'");
              ?>
              <!-- for following modal  -->

              <!-- Modal -->
              <div class="modal fade" id="following" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel"> All Following</h5>
                      <a type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</i></a>
                    </div>
                    <div class="modal-body">
                      <?php while ($following = mysqli_fetch_array($get_following)) {
                        if ($following['userOne'] != $_SESSION['id']) {
                          $row =  fetchRow('user', 'userId=' . $following['userOne'] . '');
                        }
                        if($following['userTwo'] != $_SESSION['id']){
                          $row =  fetchRow('user', 'userId=' . $following['userTwo'] . '');

                        }
                      ?>
                          <div class='alert alert-secondary show rounded-pill'>
                            <a class="text-primary" href='home.php?id=<?= $row['userId'] ?>'> <img width="50px" class="rounded-circle" src='dp/<?= $row['dp'] ?>' /></a>
                            <a href='home.php?id=<?= $row['userId'] ?>' class='w3-bar-item w3-button '><?= $row['firstName'] . " " . $row['lastName'] ?></a>
                          </div>
                      <?php }
                       ?>
                    </div>
                  </div>
                </div>
              </div>
              <!-- for following modal end -->
              <div class="row">
                <div class="col-6">
                  <div><a class="btn  text-light mr-2 text-dark font-weight-bold" data-bs-toggle="modal" data-bs-target="#following"> <b><?php echo mysqli_num_rows($get_following) ?></b>
                      <br>Following</a></div>
                </div>

                <!-- for follower modal -->
                <!-- Modal -->
                <div class="modal fade" id="follower" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">All Follower</h5>
                        <a type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</i></a>
                      </div>
                      <div class="modal-body">
                        <?php while ($follower = mysqli_fetch_array($get_follower)) {
                          $row =  fetchRow('user', 'userId=' . $follower['receiverId'] . '');
                        ?>
                          <div class='alert alert-secondary show rounded-pill'>
                            <a class="text-primary" href='home.php?id=<?= $row['userId'] ?>'> <img width="50px" class="rounded-circle" src='dp/<?= $row['dp'] ?>' /></a>
                            <a href='home.php?id=<?= $row['userId'] ?>' class='w3-bar-item w3-button '><?= $row['firstName'] . " " . $row['lastName'] ?></a>
                          </div>
                        <?php } ?>
                      </div>

                    </div>
                  </div>
                </div>
                <!-- follower modal end  -->
                <div class="col-6">
                  <div><a class="btn  text-light text-dark font-weight-bold" data-bs-toggle="modal" data-bs-target="#follower"> <b><?php echo mysqli_num_rows($get_follower) ?></b> <br>
                      Followers</a></div>
                </div>
              </div>
            </div>
          </div>
          <div class="text-center"> <b style="font-size: 16px;">Uploads</b></div>
          <br>
          <div class="row">
            <?php image($userid); ?>
          </div>
          <!-- // added test -->
        </div>
      </div>
    </div>
  </div>
  </div>

  <!-- end profile section -->
  </body>

  </html>
<?php
}
?>