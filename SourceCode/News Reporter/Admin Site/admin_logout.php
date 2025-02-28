<?php
include('../User Site/connections.php');
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_nickname'])){
    unset ($_SESSION['admin_id']);
    unset ($_SESSION['admin_nickname']);
    session_destroy();
    header('Location: admin_login.php');
}
?>