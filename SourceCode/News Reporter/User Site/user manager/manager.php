<?php
include('user_header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="../css/manager.css" >
    <title>Manage Post Page</title>
</head>
<body>
    <!-- category (thể loại)-->
    <div class="category-list">
    <ul class="list-unstyled list-inline">
                <li><a href="../pages/home.php"><i class="icon-home fa-solid fa-house"></i></a></li>
                <li class="list-inline-item"><a href="../pages/business.php">Business</a></li>
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
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $record_per_page = 6;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $offset = ($page - 1) * $record_per_page;

        $query = "SELECT * FROM User, Post 
                        WHERE User.user_id = Post.user_id 
                        AND Post.user_id = '$user_id' 
                        AND status = 1";
        $result = $conn->query($query);

        $total_approved_posts = $result->num_rows;

        $total_pages = ceil($total_approved_posts / $record_per_page);

        $query = "SELECT * FROM User, Post, Category 
                        WHERE Category.category_id = Post.category_id 
                        AND Post.user_id = '$user_id' 
                        AND User.user_id = Post.user_id 
                        AND status = 1
              LIMIT $offset, $record_per_page";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            echo "<div class='container p-0'>";
            echo "<div class='row card-container'>";
                while ($data = $result->fetch_assoc()) {
                    echo "<div class='col-md-4 card-lists'>";
                        echo "<div class='card'>";
                            echo "<img src='" . $data['post_image'] . "' class='card-img' alt='...'>";
                            echo "<div class='card-body'>";
                            echo "<a class='post_title' href='../pages/read.php?post_id=" . $data['post_id'] . "'>" . $data['post_title'] . "</a>";
                            echo "<p class='card-text'>" . shorten_text($data['post_content'], 100) . "</p>";
                                echo "<div class='card-button'>";
                                    echo "<a href='delete.php?post_id=" . $data['post_id'] . "' class='btn btn-danger'>Delete</a>";
                                    echo "<a href='update.php?post_id=" . $data['post_id'] . "' class='btn btn-primary'>Update</a>";
                                echo "</div>";
                            echo "</div>";
                        echo "</div>";
                    echo "</div>";
                }
            echo "</div>";
        echo "</div>";

            // Pagination links (Liên kết phân trang)
            echo "<div class='container'>";
                echo "<div class='row text-center'>";
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
        } else {
        echo 'Error: No data found !';
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
    ?>

<?php 
include('user_footer.php');
?>