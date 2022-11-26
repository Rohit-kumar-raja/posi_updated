<style>
    .card-title2{
        font-size: 13px!important;
    }
    .card{
        border: 1px solid rgb(255 255 255 / 13%)!important;
    }
    .btn-sm{
        font-size:7px!important;
    }
</style>

<?php
// include "posi_header.php";
// error_reporting(0);
?>

<body>
    <div class="container" style="margin-top:65px">
        <h3 class="text-center">Your Search Is Here</h3>
        <hr>
        <div class="row">
            <?php
            include("database/connection.php");
            $database = "posigraph_socialplexus";
            include "framwork/main.php";
            mysqli_select_db($conn, $database);

            $a = $_GET['a'];
            $a = trim($a);
            //$b=str_replace(" ","%",$a);

            function friend_is($userid)
            {
                $me = $_SESSION['id'];
                $friends = false;
                $frind_data = fetchResult('friends', 'userOne=' . $userid . ' or userTwo=' . $userid . '');
                while ($friend_row = mysqli_fetch_array($frind_data)) {
                    if ($friend_row['userOne'] == $me || $friend_row['userOne'] == $me) {
                        $friends = true;
                        break;
                    } else {
                        $friends = false;
                    }
                }
                return $friends;
            }



            if (strlen($a) == 0)
                header("location:./home.php");

            //search by email id
            if (strpos($a, '@') !== false) {
                $query = "select *  from user where  verStatus='verified' && email='{$a}'";
                $result = mysqli_query($conn, $query);
                if ($result) {
                    if (mysqli_num_rows($result) >= 1) {
                        while ($row = mysqli_fetch_array($result)) {
                            $fname = $row['firstName'];
                            $lname = $row['lastName'];
                            if (isset($row['dp']) && $row['dp'] != '') {
                                $dp = $row['dp'];
                            } else {
                                if ($row['gender'] == 'male') {
                                    $dp = 'default_male.png';
                                } else {
                                    $dp = 'default_female.png';
                                }
                            }
            ?>

                            <div class='col-md-3 col-6 mb-3'>
                                <div class='card'>
                                    <img class='card-img-top' src='dp/<?= $dp ?>' alt='Card image' style=''>
                                    <div class='card-body'>
                                        <h4 class='card-title card-title1'><?= $fname . ' ' . $lname ?></h4>
                                        <div class='row'>
                                            <div class='col-6'>
                                                <a href='./profile/profile.php?id=<?= $id ?>'>
                                                    <p><button class='btn btn-success btn-sm'>View Profile</button></p>
                                                </a>
                                            </div>
                                            <div class='col-6'>
                                                <a id='request' href='#'><button data-id='<?= $row['userId'] ?>' class='request-btn btn btn-sm btn-info'>Follow</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                    } else
                        echo "<script>window.alert('sorry no such record')</script>";
                } else
                    mysqli_error($conn);
            } else {
                $condition1 = '';
                $condition2 = '';
                $q = explode(" ", $a);
                foreach ($q as $word) {
                    $condition1 .= "firstName LIKE '%" . mysqli_real_escape_string($conn, $word) . "%' OR ";
                    $condition2 .= "lastName LIKE '%" . mysqli_real_escape_string($conn, $word) . "%' OR ";
                }
                $condition1 = substr($condition1, 0, -4);
                $condition2 = substr($condition2, 0, -4);
                $query = "select * from user where " . $condition1 . " OR " . $condition2 . " LIMIT 50";
                $result = mysqli_query($conn, $query);
                if ($result) {
                    if (mysqli_num_rows($result) >= 1) {

                        while ($row = mysqli_fetch_array($result)) {
                            $fname = $row['firstName'];
                            $lname = $row['lastName'];
                            if (isset($row['dp']) && $row['dp'] != '') {
                                $dp = $row['dp'];
                            } else {
                                if ($row['gender'] == 'male') {
                                    $dp = 'default_male.png';
                                } else {
                                    $dp = 'default_female.png';
                                }
                            }
                            $me = $_SESSION['id'];
                            $request_user = fetchRow('friend_request', 'senderId=' . $me . ' && receiverId=' . $row['userId'] . '');


                        ?>
                            <div class='col-md-3 col-6 mb-3 p-0'>
                                <div class='card'>
                                    <img class='card-img-top' src='dp/<?= $dp ?>' alt='Card image' style="width: 100px;margin: 0 auto;border-radius: 50px;">
                                    <div class='card-body'>
                                        <h4 class='card-title card-title2'><?= $fname . ' ' . $lname ?></h4>
                                        <div class='row'>
                                            <div class='col-6'>
                                                <a href='./profile/profile.php?id=<?= $row['userId'] ?>'>
                                                    <p><button class='btn btn-success btn-sm'> <i class="fas fa-user"></i> View Profile</button></p>
                                                </a>
                                            </div>
                                            <div class='col-6'>
                                                <?php if (friend_is($row['userId'])) { ?>
                                                    <a class="btn btn-sm btn-primary" href="#"> <i class='fas fa-user-friends'></i> Friends </a>
                                                <?php } else if ($request_user != '') {
                                                ?>
                                                    <a class="btn btn-sm btn-secondary" href="#"> <i class="fas fa-user-check"></i> Request sent </a>

                                                <?php } else { ?>
                                                    <a class="btn btn-sm btn-info" href="#" onclick="follow_btn('<?= $row['userId']  ?>','request','<?= $row['firstName'] ?>')" id="follow"><i class="fas fa-user-plus"></i> Follow
                                                    </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
            <?php
                        }
                    } else
                        echo "<script>window.alert('sorry no such record')</script>";
                } else
                    mysqli_error($conn);
            }
            ?>
        </div>


    </div>

</body>


</html>