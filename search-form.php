<?php
session_start();
include("./database/connection.php");
include("./database/getMsgNotif.php");
$database = "posigraph_socialplexus";
mysqli_select_db($conn, $database);
$me = $_SESSION['id'];
$user = mysqli_query($conn, "select * from user where userId='$me'");
$user = mysqli_fetch_array($user);

include "posi_header.php";
?>

<div class="container" style="margin-top: 75px;">
    <h3 class="text-center">Search Here</h3>
    <hr>
    <div class="row">
        <div class="col-8">
            <input onkeyup="search()" type="text" placeholder="Search Here Your friends .. " id="srch_input" class="form-control">
        </div>
        <div class="col-2">
            <button onclick="search()" id="srch" class="btn btn-success"><i class="fa fa-search" aria-hidden="true"></i>
                Search </button>
        </div>
    </div>
</div>

<div id="search">

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>
    function search() {
        v = $("#srch_input").val();
        if (v == "")
            window.alert("please enter name or email")
        else
            // getting data fo infrastructure image and youtube video
            var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            document.getElementById("search").innerHTML = this.responseText;
        }
        xmlhttp.open("GET", 'search.php?a=' + v, true);
        xmlhttp.send();

    }


    function follow_btn(userId, buttonName, userName) {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            document.getElementById("follow").innerHTML = this.responseText;
        }

        callFun = new FormData();
        callFun.append("buttonName", buttonName);
        callFun.append("userId", userId);
        callFun.append("userName", userName);
        xhttp.open("POST", "friends/functions.php", true);
        xhttp.send(callFun);
    }
</script>