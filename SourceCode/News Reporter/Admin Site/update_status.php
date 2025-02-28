<?php
include('../User Site/connections.php');

if (isset($_POST['status'])) {
    $post_id = $_POST['post_id'];
    $status = $_POST['status'];

    // Update the status based on the value
    if ($status === 'approve') {
        $new_status = 1; // Approved
    } elseif ($status === 'reject') {
        $new_status = 2; // Rejected
    }

    // Update the status in the database
    $query = "UPDATE Post SET status = $new_status WHERE post_id = $post_id";
    $result = $conn->query($query);

    if ($result === TRUE) {
        echo 'Status updated successfully';
        // Redirect back to the page displaying the posts
        echo '<script>window.history.back();</script>';
        exit;
    } else {
        echo 'Status update failed';
    }

    $conn->close();
}
?>