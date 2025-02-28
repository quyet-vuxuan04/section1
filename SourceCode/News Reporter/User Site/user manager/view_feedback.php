<?php
// ob_start();
include('user_header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback Page</title>
</head>
<body>
    <!-- category (thể loại)-->
    <div class="category-list">
            <ul class="list-unstyled list-inline">
                <li><a href="../pages/home.php"><i class="icon-home fa-solid fa-house"></i></a></li>
                <li class="list-inline-item"><a  href="../pages/business.php">Business</a></li>
                <li class="list-inline-item"><a href="../pages/technology.php">Technology</a></li>
                <li class="list-inline-item"><a href="../pages/sports.php">Sports</a></li>
                <li class="list-inline-item"><a href="../pages/beauty.php">Beauty</a></li>
                <li class="list-inline-item"><a href="../pages/sociaty.php">Sociaty</a></li>
                <li class="list-inline-item"><a href="../pages/todayinworld.php">Today in World</a></li>
                <li class="list-inline-item"><a href="../pages/about_us.php">About Us</a></li>
                <li class="list-inline-item"><a href="../pages/contact_us.php">Contact Us</a></li>
            </ul>
    </div>
</body>
</html>

<?php
$record_per_page = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $record_per_page;

// Retrieve approved records from the database (Truy xuất các bản ghi đã được phê duyệt từ cơ sở dữ liệu)
$query = "SELECT * FROM Feedback, User WHERE Feedback.user_id = User.user_id AND User.user_id = $user_id";
$result = $conn->query($query);

// Calculate the total number of approved posts (Tính tổng số bài viết được phê duyệt)
$data = $result->num_rows;

//  Calculate the total number of pages based on the number of approved posts and the records per page (
$total_pages = ceil($data / $record_per_page);


if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM Feedback, User 
          WHERE Feedback.user_id = User.user_id AND User.user_id = $user_id 
          LIMIT $offset, $record_per_page";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($data = $result->fetch_assoc()) {
            $fb_id = $data['fb_id'];
            ?>
            <div class="container mt-3" style="border:1px solid #ccc; padding:20px;margin-bottom:30px !important">
                <h3>Feedback</h3>
                <p>Feedback's Title: <?php echo $data['fb_title']; ?></p>
                <p>Feedback's Content: <?php echo $data['fb_content']; ?></p>
                <?php
                $response_query = "SELECT * FROM Response, Feedback
                                   WHERE Response.fb_id = Feedback.fb_id AND Feedback.fb_id = '$fb_id'";
                $response_result = $conn->query($response_query);

                if ($response_result->num_rows > 0) {
                    $response_data = $response_result->fetch_assoc();
                    ?>
                    <h3>Response</h3>
                    <p>Response Title: <?php echo $response_data['response_title']; ?></p>
                    <p>Response Content: <?php echo $response_data['response_content']; ?></p>
                    <?php
                }else{
                    echo '<h3>Response</h3>';
                    echo '<div class="alert alert-info">
                    <strong>Note!</strong> Please be patient to wait admin response your feedback !!!.
                  </div>';
                } 
                ?>
            </div>
            
            <?php

        }
    }else {
        echo "<div class='container mt-3 alert alert-warning' role='alert'>You haven't feedback yet !!!</div>";
    }
    echo "<div class='container'>";
    echo "<div class='row'>";
    echo "<div class='col-md-12 text-center mx-auto'>";
    echo "<ul class='pagination justify-content-center'>";
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<li class='page-item";
        if ($i == $page) {
            echo " active";
        }
        echo "'><a class='page-link' href='?page=" . $i . "'>" . $i . "</a></li>";
    }
    echo "</ul>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
} 
include ('user_footer.php');
// ob_end_flush();
?>



