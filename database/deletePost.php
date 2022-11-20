<?php
include "../framwork/main.php";
if (isset($_GET['postId'])) {
     delete('posts', 'postId=' . $_GET['postId'] . ' && userId=' . $_SESSION['id'] . '');
   header('location:../home.php');
} else {
?><script>
        location.replace('<?= $_SERVER['HTTP_REFERER'] ?>')
    </script>
<?php
}
