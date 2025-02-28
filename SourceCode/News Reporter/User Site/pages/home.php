<?php
include('../header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="../css/content.css">
    <title>Home Page</title>
</head>
<body>
        <!-- category (thể loại)-->
        <div class="category-list">
            <ul class="list-unstyled list-inline">
                <li><a href="home.php"><i class="category-color icon-home fa-solid fa-house"></i></a></li>
                <li class="list-inline-item"><a  href="business.php">Business</a></li>
                <li class="list-inline-item"><a href="technology.php">Technology</a></li>
                <li class="list-inline-item"><a href="sports.php">Sports</a></li>
                <li class="list-inline-item"><a href="beauty.php">Beauty</a></li>
                <li class="list-inline-item"><a href="sociaty.php">Sociaty</a></li>
                <li class="list-inline-item"><a href="todayinworld.php">Today in World</a></li>
                <li class="list-inline-item"><a href="about_us.php">About Us</a></li>
                <li class="list-inline-item"><a href="contact_us.php">Contact Us</a></li>
            </ul>
        </div>
</body>
</html>

<!-- Pagination (phân trang) -->
<?php
$record_per_page = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $record_per_page;

// Retrieve approved records from the database (Truy xuất các bản ghi đã được phê duyệt từ cơ sở dữ liệu)
$query = "SELECT * FROM Post, Category WHERE Post.category_id = Category.category_id AND status = 1";
$result = $conn->query($query);

// Calculate the total number of approved posts (Tính tổng số bài viết được phê duyệt)
$total_approved_posts = $result->num_rows;

//  Calculate the total number of pages based on the number of approved posts and the records per page (
$total_pages = ceil($total_approved_posts / $record_per_page);

// Truy vấn lấy 10 bài đầu tiên theo thứ tự mới nhất
$query_newest = "SELECT DISTINCT Post.*, Category.*
                FROM Post
                INNER JOIN Category ON Post.category_id = Category.category_id
                WHERE status = 1
                ORDER BY rate DESC, upload_date DESC
                LIMIT 10 OFFSET " . (($page - 1) * 10);

$result_newest = $conn->query($query_newest);

// Lấy tất cả kết quả vào mảng
$newsest_post = array();
while ($data = $result_newest->fetch_assoc()) {
    $newsest_post[] = $data;
}

// Kiểm tra và hiển thị kết quả
if ((count($newsest_post) > 0 || count($combinedPosts) > 0) && $total_pages > 0) {
    echo "<div class='col-xl post_container'>";

    // Hiển thị bài đăng mới nhất
    $post_item_number = 1;
    foreach ($newsest_post as $post) {
        display_post($post, $post_item_number);
        $post_item_number++;
    }

    echo "</div>";

    // Pagination links
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
} else {
    echo 'Error: No data found!';
}

// Hàm hiển thị thông tin bài đăng
function display_post($data, $post_item_number) {
    echo "<div class='post item_" . $post_item_number . "'>";
        echo "<img class='post_img' src='" . $data['post_image'] . "' width='100%'>";
        echo "<div class='post_box'>";
            echo "<a class='post_title' href='read.php?post_id=" . $data['post_id'] . "'>" . $data['post_title'] . "</a>";
            echo "<p class='post_content'>" . ($data['post_content']) . "</p>"; 
            
            $upload_date = strtotime($data['upload_date']);
        $current_date = strtotime(date('Y-m-d H:i:s'));
        $time_diff = $current_date - $upload_date;
        $days_diff = floor($time_diff / (60 * 60 * 24));
        $time_ago = ($days_diff > 0) ? date('d/m/Y',$upload_date). ' - '. $days_diff . " days ago" : "Today";
        echo "<p class='post_time_ago'>Posted " . $time_ago . " at " . date('H:i', $upload_date) . "</p>";           
        echo "<p class='post_rating'>";
            $rating = intval($data['rate']); // Lấy giá trị đánh giá từ cột 'rate' và chuyển đổi thành số nguyên
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $rating) {
                    echo "<i class='fas fa-star'></i>"; // Sử dụng class của Font Awesome để hiển thị ngôi sao đầy
                } else {
                    echo "<i class='far fa-star'></i>"; // Sử dụng class của Font Awesome để hiển thị ngôi sao trống
                }
            }
        echo "</div>";
    echo "</div>";
}

//Shorten text for display content (Rút gọn văn bản để hiển thị nội dung)
function shorten_text($text, $max_length)
{
    if (mb_strlen($text, 'UTF-8') > $max_length) {
        $shorten_text = mb_substr($text, 0, $max_length, 'UTF-8');
        $shorten_text .= '...';
        return $shorten_text;
    }
    return $text;
}
include('../footer.php');
?>

