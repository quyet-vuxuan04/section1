<?php
include('../User Site/connections.php');
session_start();
if (isset($_SESSION['admin_id'])){
    $post_id = $_GET['post_id'];
    $query = "DELETE FROM Post WHERE post_id='$post_id'";
    $result = $conn->query($query);

    if ($result == TRUE){
        header('Location: home.php');
        exit();
    }
}else {
    echo 'You have to login first !!!'. '<br>';
}
?>

<a href="home.php">Home</a>