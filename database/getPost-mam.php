<?php
$like_post_num = 0;
//include("connection.php");
//$database="plexus";
include "database/connection.php";
include "database/like.php";
include "database/dislike.php";
include "database/p1like.php";
include "database/p2like.php";
$database = "posigraph_socialplexus";
$table = "posts";
mysqli_select_db($conn, $database);

// graph percentange
?>
<div class="container" style="padding-left:0px!important;padding-right:0px!important;">


    <?php

    function getPost($from, $count)
    {
        global $conn;
        $battle_condition = '';
        $result = fetchResult('user', '`userId`=' . $_SESSION['id'] . '');

        if ($result) {

            $row = mysqli_fetch_array($result);
            // echo "<pre>";
            // print_r($row);
            $post = $row['post'];

            // battle post start here
            $friends = getFriends($_SESSION['id']);
            $me = $_SESSION['id'];
            if (isset($_GET['post_id']) && $_GET['post_id'] != '' && isset($_GET['post_type']) && $_GET['post_type'] == 'battle') {
                $battle_condition = "`id`='" . $_GET['post_id'] . "'";
            } elseif (!isset($_GET['post_type'])) {
                $battle_condition = "player1_id IN($friends,$me) OR player2_id IN($friends,$me) ORDER BY date_of_creation DESC";
            }
            if ($battle_condition != '') {
                $battle = "select * from battle where $battle_condition ";

                // echo "<pre>";
                // print_r($row);
                $battle_total_users = mysqli_query($conn, $battle);
                $totalbattle = mysqli_num_rows($battle_total_users);

                if ($totalbattle > 0) {
                    while ($battle_total_users_data = mysqli_fetch_array($battle_total_users)) {
                        if ($battle_total_users_data['player1_post'] != '' && $battle_total_users_data['player2_post'] != '') {  ?>
                            <div class="">
                                <div class="row  ">
                                    <!-- first user details here -->
                                    <div class="col-6" style="padding-right: 5px;padding-left: 5px;">
                                        <?php
                                        $details_of_user_one = fetchRow('user', '`userId` = ' . $battle_total_users_data['player1_id'] . '');
                                        ?>

                                        <img src="dp/<?= $details_of_user_one['dp']; ?>" alt="Avatar4 11" class="w3-left w3-circle w3-margin-right profile-logo" style="margin-left: 10px;">

                                        <a href="./profile/profile.php?id=<?= $details_of_user_one['userId'] ?>">
                                            <span class="font-weight-bold"><?= $details_of_user_one['firstName'] . ' ' . $details_of_user_one['lastName']; ?></span></a>
                                        <hr class="w3-clear 1">
                                        <?php if ($battle_total_users_data['player1_post'] != '') { ?>

                                            <img width="100%" <?= ' src="data:image/jpeg;base64,' . base64_encode($battle_total_users_data['player1_post']) . '"' ?> class="w3-margin-bottom post_image" />

                                        <?php } else {
                                        ?>
                                            <span class="font-weight-bold text-success">Player1 Image not uploaded yet!</span>
                                        <?php } ?>
                                    </div>
                                    <!-- first user details end -->
                                    <!-- second user details here  -->
                                    <div class="col-6" style="padding-right: 5px;padding-left: 5px;">
                                        <?php
                                        $color = "red";
                                        $details_of_user_two = fetchRow('user', '`userId` = ' . $battle_total_users_data['player2_id'] . '');
                                        ?>
                                        <!-- profile image  of the user  -->

                                        <a href="./profile/profile.php?id=<?= $details_of_user_two['userId'] ?>">

                                            <span class="font-weight-bold 1" style="margin-right: 70px;"><?= $details_of_user_two['firstName'] . ' ' . $details_of_user_two['lastName']; ?></span></a>
                                        <img src="dp/<?= $details_of_user_two['dp']; ?>" alt="Avatar4" class="w3-left w3-circle w3-margin-right profile-logo 1" style="float: inherit!important;margin: -30px!important;">
                                        <hr class="w3-clear 2">
                                        <?php if ($battle_total_users_data['player2_post'] != '') { ?>
                                            <img width="100%" <?= ' src="data:image/jpeg;base64,' . base64_encode($battle_total_users_data['player2_post']) . '"' ?> class="w3-margin-bottom post_image" />
                                        <?php } else {
                                        ?>
                                            <span class="font-weight-bold text-success">Player2 Image not uploaded yet!</span>
                                        <?php } ?>
                                    </div>
                                </div>
                                <!--</div>-->
                                <!-- second user details end  -->
                                <!-- like dislike graph for battle start here-->

                                <?php
                                $user_one_total_like = totalp1Like($battle_total_users_data['player1_id'], 1);
                                $user_two_total_like = totalp1Like($battle_total_users_data['player2_id'], 2);
                                $user_one_total_like = $user_one_total_like == '' ? 0 : $user_one_total_like;
                                $user_two_total_like = $user_two_total_like == '' ? 0 : $user_two_total_like;
                                // adding  calculating the total like of the user one and user two for getting the percentage of the total like
                                $battle_user_one_and_two_total_like = $user_one_total_like + $user_two_total_like;
                                if (($user_one_total_like + $user_two_total_like) == 0) {
                                    $user_one_total_like_percentage = 50;
                                    $user_two_total_like_percentage = 50;
                                } else {
                                    $user_one_total_like_percentage = round($user_one_total_like / $battle_user_one_and_two_total_like * 100);
                                    $user_two_total_like_percentage = round($user_two_total_like / $battle_user_one_and_two_total_like * 100);
                                }

                                ?>
                                <div style="width:95%;margin:0 auto;">
                                    <div class="dislike_base-graph">
                                        <button type="button" data-pid="<?= $battle_total_users_data['player1_id'] ?>" data-bid="<?= $battle_total_users_data['id'] ?>" class="p1batlike w3-theme-d1" style="border: none;   background: #fff;">
                                            <i style="color:<?= $color ?>" id="<?= $battle_total_users_data['player1_id'] ?>" class="fa fa-heart-o heart-graph text-danger"></i>

                                            <span class="p1batlike<?= $battle_total_users_data['id'] ?>" id="p1batlike<?= $battle_total_users_data['player1_id'] ?>" style="color:#000;"><?= $user_one_total_like ?></span>
                                        </button>
                                        <div class="like-graph" id="graph1<?= $battle_total_users_data['id'] ?>" style="width:<?= $user_one_total_like_percentage; ?>%"><?= $user_one_total_like_percentage; ?>%</div>
                                        <div class="dislike-graph" id="graph2<?= $battle_total_users_data['id'] ?>" style="width:<?= $user_two_total_like_percentage; ?>%"><?= $user_two_total_like_percentage; ?>%</div>

                                        <button type="button" data-pid="<?= $battle_total_users_data['player2_id'] ?>" data-bid="<?= $battle_total_users_data['id'] ?>" class="p2batlike w3-theme-d1" style="border: none; background: #fff;">
                                            <i style="color:<?= $color ?>" id="<?= $battle_total_users_data['player2_id'] ?>" class="fa fa-heart heart-graph text-danger"></i>

                                            <span class="p2batlike<?= $battle_total_users_data['id'] ?>" id="p2batlike<?= $battle_total_users_data['player2_id'] ?>" style="color:#000;"><?= $user_two_total_like ?></span>
                                        </button>
                                    </div>
                                </div>
                                <!-- battle like dislike end  -->
                            <?php
                        }
                    }
                }
            }


            // battle post ends here

            //  for single for showing this all contest
            if ($post == "yes") {

                //          fetch all post of id and his/her friend and show it
                //          get all friends id
                $friends = getFriends($_SESSION['id']);
                $me = $_SESSION['id'];

                if (isset($_GET['post_id']) && $_GET['post_id'] != '' && isset($_GET['post_type']) && $_GET['post_type'] != 'battle') {
                    $posts = "select * from posts  where `postId`='" . $_GET['post_id'] . "'";
                } elseif (!isset($_GET['post_type'])) {
                    $posts = "select * from posts  where userId IN($friends,$me) ORDER BY  postDate DESC LIMIT $from,$count";
                }
                //}
                $postList = mysqli_query($conn, $posts);
                $total = mysqli_num_rows($postList);

                if ($total > 0) {
                    while ($list = mysqli_fetch_array($postList)) {
                        $like = myLike($list['postId'], $_SESSION['id']);

                        if ($like) {
                            $color = "blue";
                        } else {
                            $color = "red";
                        }

                        //check post type text or img
                        $query = "select userId,firstName,lastName,dp from user where userId='{$list['userId']}'";
                        $result = mysqli_query($conn, $query);
                        $user = mysqli_fetch_assoc($result);

                        $postDate = date('F j,Y,g:i a', strtotime($list['postDate']));
                        //               <!--post area start-->
                        if ($list['type'] == "text") { ?>

                                <div class="w3-container w3-card w3-white w3-round w3-margin"><br>

                                    <button type="button" data-pid="<?= $list['postId'] ?>" class="dislike-btn w3-button w3-theme-d1 w3-margin-bottom">

                                        <i style="color:<?= $color ?>" id="dislike1<?= $list['postId'] ?>" class="fa fa-thumbs-up"></i> &nbsp;
                                        <span id="dislike<?= $list['postId'] ?>">
                                            <?php totaldisLike($list['postId']); ?><?php totaldisLike($list['postId']); ?>
                                        </span>
                                    </button>
                                    <!-- // ajit added -->
                                    <button type="button" data-pid="<?= $list['postId'] ?>" class="comment-btn w3-button w3-theme-d2 w3-margin-bottom"><i class="fa fa-comment"></i> &nbsp;Comment</button>
                                </div>

                            <?php
                        } else { ?>


                                <div class="w3-container w3-card w3-white w3-round w3-margin w-100 "><br>
                                    <div class="row">
                                        <div class="col-10">
                                            <img src="dp/<?= $user['dp'] ?>" alt="Avatar4" class="w3-left w3-circle w3-margin-right" style="width:37px;border-radius:50%;margin-bottom:8px;">
                                            <a href="./profile/profile.php?id=<?= $list['userId'] ?>">
                                                <span class="font-weight-bold"><?= $user['firstName'] . ' ' . $user['lastName'] ?> </span>
                                            </a>
                                        </div>

                                        <!-- <div class="col-2">
                                            <div class="dropdown">
                                                <a class=" p-1" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                </a>
                                                <div id="dropdown" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="#"> Share</a>
                                                    <?php if ($user['userId'] == $_SESSION['id']) { ?>
                                                        <a class="dropdown-item" href="./database/deletePost.php?postId=<?= $list['postId'] ?>">Delete Image</a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div> -->

                                    </div>


                                    <!-- <span class="w3-right w3-opacity font-weight-bold">Posted Date : <?= $postDate ?></span> -->
                                    <hr class="w3-clear 3">
                                    <!-- <p><?= $list['postContent'] ?></p> -->

                                    <img src="<?= 'imagePost/' . $list['postImage'] ?>" style="width:100%;height:430px!important" class="w3-margin-bottom post_image">
                                    <p style="margin: 0px;padding: 0px 20px;background: #fff;"><?= $list['postContent'] ?></p>

                                    <!-- like dislike graph -->
                                    <span style="display:none;">
                                        <?php
                                        $like_post_num = totalLike($list['postId']);
                                        // $like1 = totalLike($list['postId']);
                                        $hate_post_num = totaldisLike($list['postId']);
                                        //print_r($list['postId']);

                                        // $like_post_num = 5;
                                        // $hate_post_num = 6;

                                        $sum = $like_post_num + $hate_post_num;

                                        if (($like_post_num + $hate_post_num) == 0) {
                                            $like_post_num = 1;
                                            $hate_post_num = 1;
                                            $sum = 1;
                                        }
                                        $like_percent = round($like_post_num / $sum * 100);
                                        $hate_percent = round($hate_post_num / $sum * 100);

                                        //  echo totalLike($list['postId']);
                                        // get user data
                                        $query = "select * from user where userId=" . $_SESSION['id'];
                                        $result = mysqli_query($conn, $query);
                                        $user = mysqli_fetch_array($result);

                                        ?>
                                    </span>
                                    <div style="position:relative;width: 95%;margin: 0 auto;">
                                        <div class="dislike_base-graph">
                                            <span>
                                                <button type="button" data-pid="<?= $list['postId'] ?>" class="like-btn w3-theme-d1 " style="border: none;
                                          background: #fff;"><i style="color:<?= $color ?>" id="<?= $list['postId'] ?>" class="fa fa-heart-o heart-graph text-danger"></i> &nbsp;
                                                    <span class="like<?= $list['postId'] ?>" id="like<?= $list['postId'] ?>" style="color:#000;"><?= totalLike($list['postId']); ?></span></button>
                                                <!-- <i class="fa fa-heart-o heart-graph"></i> -->
                                            </span>

                                            <div class="like-graph" id="graph1<?= $list['postId'] ?>" style="width: <?= $like_percent; ?>%"><?= $like_percent; ?>%</div>
                                            <div class="dislike-graph" id="graph2<?= $list['postId'] ?>" style="width: <?= $hate_percent; ?>%"><?= $hate_percent; ?>%</div>

                                            <button type="button" data-pid="<?= $list['postId'] ?>" class="dislike-btn w3-theme-d1 " style="border: none;
                                          background: #fff;"><i style="color:<?= $color ?>" id="<?= $list['postId'] ?>" class="fa fa-heart heart-graph text-danger"></i>
                                                &nbsp;<span class="dislike<?= $list['postId'] ?>" id="dislike<?= $list['postId'] ?>" style="color:#000;"><?= totaldisLike($list['postId']); ?></span></button>

                                            <!-- <span><i class="fa fa-heart heart-graph"></i></span> -->

                                        </div>
                                    </div>
                                    <!-- // like dislike graph -->
                                    <h6 style="font-size: 12px;text-align:center;color:#7c7c7c;margin:0px;width:100%;"> <span class="text-success"><?= mysqli_num_rows(fetchResult('likes', 'postId=' . $list['postId'] . '')) + mysqli_num_rows(fetchResult('dislikes', 'postId=' . $list['postId'] . '')) ?> votes</span> <span class="text-danger"><?= mysqli_num_rows(fetchResult('comments', 'postId=' . $list['postId'] . '')) ?> comment</span> </h6>

                                    <!-- comment button -->
                                    <div class="comment-section">
                                        <div class="user-profile"><img src="dp/<?= $user['dp']; ?>" style="width: 30px;
                                    height: 30px;
                                    margin: 5px;
                                    margin-top: 12px;
                                    margin-left: 14px;" /></div>
                                        <div class="user-comment">
                                            <button type="button" class="comment-btn" data-pid="<?= $list['postId'] ?>">comment</button>
                                        </div>
                                    </div>
                                    <!-- //comment button -->


                                </div>

                            <?php } ?>

                            <!--        post area ends here-->
                    <?php
                    }
                }
                //                 if(total) and while close
                else {
                    echo mysqli_error($conn);
                }

                    ?>
                    <!--      inner php tag  "above result" is close  -->



                    <!--    bellow pair of inner if 'post=yes' close '}' start else '{' and close with php tag-->
                    <?php
                } else { // if no post is there  then .load only friends post..it default post welcom post

                    $friends = getFriends($_SESSION['id']);
                    if ($friends != 0) {
                        getFriendPost($from, $count);
                    } else {
                        //                 if user has no friend and no post
                        echo '<div class="w3-container w3-card w3-white w3-round w3-margin">
                  <br><h2> You have no posts </h2></div> ';
                    }
                }
            }
            //query if ends here
        }

        // ////////////////////////////////////////////////////////////////////////////////////////

        function getFriendPost($from, $count)
        {
            global $conn;

            $friends = getFriends($_SESSION['id']);
            $posts = "select * from posts  where userId IN($friends) ORDER BY  postDate DESC LIMIT $from,$count";
            $postList = mysqli_query($conn, $posts);
            $total = mysqli_num_rows($postList);
            if ($total > 0) {
                while ($list = mysqli_fetch_array($postList)) {
                    $like = myLike($list['postId'], $_SESSION['id']);
                    if ($like) {
                        $color = "blue";
                    } else {
                        $color = "red";
                    }


                    $user = fetchRow('user', '`userId`=' . $list['userId'] . '');

                    $postDate = date('F j,Y,g:i a', strtotime($list['postDate']));
                    if ($list['type'] == "text") { ?>
                        <!--                  html area-->
                        <div class="w3-container w3-card w3-white w3-round w3-margin"><br>
                            <img src="dp/<?= $user['dp'] ?>" alt="Avatar1" class="w3-left w3-circle w3-margin-right profile-logo">
                            <span class="w3-right w3-opacity"><?= $postDate ?></span>
                            <a href="./profile/profile.php?id=<?= $list['userId'] ?>">
                                <h4><?= $user['firstName'] ?></h4><br>
                            </a>
                            <hr class="w3-clear 4">

                            <p><?= $list['postContent'] ?></p>

                            <button type="button" data-pid="<?= $list['postId'] ?>" class="  like-btn w3-button w3-theme-d1 ">
                                <i style="color:<?= $color ?>" id="<?= $list['postId'] ?>" class="fa fa-thumbs-up"></i> &nbsp;
                                <span id="like<?= $list['postId'] ?>">
                                    <?php totalLike($list['postId']); ?></span></button>

                            <!-- ajit added -->
                            <button type="button" data-pid="<?= $list['postId'] ?>" class="  dislike-btn w3-button w3-theme-d1 ">
                                <i style="color:<?= $color ?>" id="<?= $list['postId'] ?>" class="fa fa-thumbs-up"></i> &nbsp;
                                <span id="dislike<?= $list['postId'] ?>">
                                    <?php totaldisLike($list['postId']); ?></span></button>
                            <!--// ajit added -->

                            <button type="button" data-pid="<?= $list['postId'] ?>" class="comment-btn w3-button w3-theme-d2 "><i class="fa fa-comment"></i> &nbsp;Comment</button>

                        </div>

                    <?php
                    } else { ?>
                        <!--                html area  -->
                        <div class="w3-container w3-card w3-white w3-round w3-margin"><br>
                            <img src="dp/<?= $user['dp'] ?>" alt="Avatar2" class="w3-left w3-circle w3-margin-right" style="border-radius:50%;width:100px;">
                            <span class="w3-right w3-opacity"><?= $postDate ?></span>
                            <a href="./profile/profile.php?id=<?= $list['userId'] ?>">
                                <h4><?= $user['firstName'] ?></h4><br>
                            </a>
                            <hr class="w3-clear 5">
                            <p><?= $list['postContent'] ?></p>
                            <img src="<?= 'imagePost/' . $list['postImage'] ?>" style="width:100%" class="w3-margin-bottom">


                            <button type="button" data-pid="<?= $list['postId'] ?>" class="like-btn w3-button w3-theme-d1 ">
                                <i style="color:<?= $color ?>" id="<?= $list['postId'] ?>" class="fa fa-thumbs-up"></i> &nbsp;
                                <span id="like<?= $list['postId'] ?>">
                                    <?php totalLike($list['postId']); ?></span></button>

                            <!-- ajit added -->
                            <button type="button" data-pid="<?= $list['postId'] ?>" class="dislike-btn w3-button w3-theme-d1 ">
                                <i style="color:<?= $color ?>" id="<?= $list['postId'] ?>" class="fa fa-thumbs-up"></i> &nbsp;
                                <span id="dislike<?= $list['postId'] ?>">
                                    <?php totaldisLike($list['postId']); ?></span></button>
                            <!-- // ajit added -->

                            <button type="button" data-pid="<?= $list['postId'] ?>" class="comment-btn w3-button w3-theme-d2 "><i class="fa fa-comment"></i> &nbsp;Comment</button>
                        </div>

        <?php }
                }
            }
        }
        // ////////////////////////////////////////////////////////////////////////////////////////////

        function getFriends($id)
        {
            global $conn;
            $i = 0;
            $friendId[] = 0;
            $query = "select userOne,userTwo from friends where userOne=$id or userTwo=$id"; // when i'am 1st col,get friend Id from userTwo
            $friends = mysqli_query($conn, $query);
            if ($friends) {
                if (mysqli_num_rows($friends) >= 1) {
                    while ($row = mysqli_fetch_array($friends)) {

                        if ($row['userOne'] == $id) {
                            $friendId[$i] = $row['userTwo'];

                            $i++;
                        } else {
                            $friendId[$i] = $row['userOne'];

                            $i++;
                        }
                    }

                    $str = implode(',', $friendId);
                    return $str;
                } else {
                    return 0;
                }
            } else {
                mysqli_error($conn);
            }
        }
        //  ////////////////////////////////////////////////////////////////
        ?>

        <style>
            .like-graph,
            .dislike-graph {
                height: 30px;
                color: #fff;
                text-align: center;
                font-weight: 900;
                font-size: 12px;
                padding: 7px 30px 5px 14px;
            }

            .like-graph {
                border-radius: 20px 0px 0px 20px;
                background: #fd012f;
                margin-left: 10px;
            }


            .dislike-graph {
                border-radius: 0px 20px 20px 0px;
                background: #000;
                margin-right: 10px;
            }

            .dislike_base-graph {
                /* position: absolute; */
                bottom: 32px;
                width: 100%;
                display: flex;
                background: #fff;
                padding: 5px;
            }

            .heart-graph {
                font-size: 26px !important;
                margin: 0 5px;
            }

            /* user comment css */
            .comment-section {
                display: flex;
                width: 90%;
                margin: 0 3%;
            }

            .comment-section .user-profile img {
                border-radius: 50%;
            }

            .comment-section .user-comment button {
                border-radius: 100px;
                width: 100% !important;
                border: 1px solid #dcdcdc;
                padding: 7px;
                text-align: left;
                color: #dcdcdc;
                background: #fff !important;
            }

            /* .comment-section .user-comment input::placeholder {
        color: #959595;
    } */

            .comment-section .user-comment {
                width: 95% !important;
                margin: 10px auto;
            }

            @media(max-width:576px) {
                .post_image {
                    height: 200px;
                }

                #dropdown {
                    left: -90% !important;
                }
            }

            button:focus {
                border: none !important;
                outline: none !important;
            }

            p {
                padding-top: 10px;
            }

            .profile-logo {
                width: 37px;
                border-radius: 50%;
                margin-bottom: 8px;
            }

            #dropdown {
                left: -50% !important;
            }
        </style>