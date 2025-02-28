
<?php
include('../header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Search Result Page</title>
</head>
<body>                
        <!-- category (thể loại)-->
        <div class="category-list">
            <ul class="list-unstyled list-inline">
                <li><a class="category-color" href="home.php"><i class="category-color icon-home fa-solid fa-house"></i></a></li>
                <li class="list-inline-item"><a href="business.php">Business</a></li>
                <li class="list-inline-item"><a href="technology.php">Technology</a></li>
                <li class="list-inline-item"><a href="sports.php">Sports</a></li>
                <li class="list-inline-item"><a href="beauty.php">Beauty</a></li>
                <li class="list-inline-item"><a href="sociaty.php">Sociaty</a></li>
                <li class="list-inline-item"><a href="todayinworld.php">Today in World</a></li>
                <li class="list-inline-item"><a href="about_us.php">About Us</a></li>
                <li class="list-inline-item"><a href="contact_us.php">Contact Us</a></li>
            </ul>
        </div>
    </header>
</body>

</html>

<?php
if (isset($_SESSION['search_results'])) {
    $search_results = $_SESSION['search_results'];

    if (!empty($search_results)) {
        foreach ($search_results as $result) {
            echo "<div class='post_container container'>";
                echo "<div class='row post_row'>";
                    echo "<div class='post_card'>";
                        echo "<div class='post_image col-4'>";
                            echo "<img class='post_img' src='" . $result['post_image'] . "' width='100%'>";
                        echo "</div>";

                        echo "<div class='post_box col-8'>";
                            echo "<a class='post_title' href='read.php?post_id=" . $result['post_id'] . "'>" . $result['post_title'] . "</a>";
                            echo "<p class='post_content'>" . ($result['post_content']) . "</p>"; 
                                $upload_date = strtotime($result['upload_date']);
                                $current_date = strtotime(date('Y-m-d H:i:s'));
                                $time_diff = $current_date - $upload_date;
                                $days_diff = floor($time_diff / (60 * 60 * 24));
                                $time_ago = ($days_diff > 0) ? $days_diff . " days ago" : "Today";
                            echo "<p class='post_time_ago'>Posted " . $time_ago . " at " . date('H:i', $upload_date) . "</p>";
                            echo "<p class='post_rating'>";
                                $rating = intval($result['rate']); // Lấy giá trị đánh giá từ cột 'rate' và chuyển đổi thành số nguyên
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $rating) {
                                        echo "<i class='fas fa-star'></i>"; // Sử dụng class của Font Awesome để hiển thị ngôi sao đầy
                                    } else {
                                        echo "<i class='far fa-star'></i>"; // Sử dụng class của Font Awesome để hiển thị ngôi sao trống
                                    }
                                }
                            echo "</div>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
            echo " </div>";
        }
    } else {
        
        echo "No results found.";
    }
    unset($_SESSION['search_results']);
} else {

    echo "<div class='container'>
        <div  class='alert alert-info'>
        No search results found !!!.
        </div>
    </div>";
    
}
include("../footer.php");
?>
