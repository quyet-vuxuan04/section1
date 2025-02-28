<?php
ob_start();
include('../header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/read.css">
    <title>Read Post Page</title>  
</head>
<body>
        <!-- category (thể loại)-->
        <div class="category-list">
            <ul class="list-unstyled list-inline">
                <li><a href="home.php"><i class="icon-home fa-solid fa-house"></i></a></li>
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

<div class="detail">
<div class="container mt-4">
    <div class="row custom-small-row">
        <div class="col-lg-8 col-md-12 content-col">
            <?php
            if (isset($_GET['post_id'])) {
                $post_id = $_GET['post_id'];
                $query = "SELECT * FROM Post
                    INNER JOIN Category ON Post.category_id = Category.category_id
                    LEFT JOIN Admin ON Post.admin_id = Admin.admin_id
                    LEFT JOIN User ON Post.user_id = User.user_id
                    WHERE Post.post_id = '{$post_id}'";

                $result = $conn->query($query);

                if ($result && $result->num_rows > 0) {
                    $data = $result->fetch_assoc();
                    // content 
                    echo "<div class='content item_1 '>";
                    echo "<div class='row content-row'>";
                    echo "<div class='content-item px-5'>";
                    echo "<h2 class='content-title fs-2  mb-0'>" . $data['post_title'] . "</h2>"; // Title displayed
                    // Display the nickname if it exists
                    echo !empty($data['admin_id']) ?
                        "<p class='content-admin Title-Name Title-AdminName'>" . $data['admin_nickname'] . "</p>" :
                        "<p class='content-user mb-0 text-body-emphasis Title-Name Title-UserName'> " . $data['user_nickname'] . "</p>";
                    echo "<p class='content-time text-black-50 bg-white fs-6 Title-Time Title-Date'>" . date('l, d/m/Y - H:i', strtotime($data['upload_date'])) . "</p>"; // Date displayed
                    echo "<img class='content-IMG img-fluid mb-2' src='" . $data['post_image'] . "' alt='' >"; // Image displayed

                    // Split the content into paragraphs
                    $paragraphs = explode("\n", $data['post_content']);

                    // Display each paragraph
                    foreach ($paragraphs as $paragraph) {
                        echo "<p class='content-post'>" . $paragraph . "</p>";
                    }
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            }
            ?>
        </div>
        <!-- see a lot -->
        <div class="col-lg-4 col-md-12 most-viewed-col ">
            <?php
            echo '<h2 class="mt-3 title_most">See a lot</h2>';
            // Most view post in right side
            $query_most = "SELECT * FROM Post ORDER BY rate DESC, RAND() LIMIT 5";
            $result_most = $conn->query($query_most);
            if ($result_most && $result_most->num_rows > 0) {
                while ($data = $result_most->fetch_assoc()) {                   
                    echo "<div class='most-viewed-item my-4'>";
                    echo "<img src='" . $data['post_image'] . "' alt='' class='most-viewed-img img-fluid mb-2' >"; // Image displayed
                    echo "<h5><a class='post_title most-viewed-title' href='read.php?post_id=" . $data['post_id'] . "'>" . $data['post_title'] . "</a></h5>"; // Title displayed
                    echo "</div>";
                }
            }
            ?>
        </div>
    </div>
</div>
</div>


        <?php
        // care about
        echo '<div class="container">';
            echo '<div class="row">';
                echo '<h2 class="mt-3 fs-2 fw-semibold title_most title_most_bottom">Có thể bạn quan tâm</h2>';

                $query_random = "SELECT * FROM Post ORDER BY RAND() LIMIT 5";
                $result_random = $conn->query($query_random);

                if ($result_random && $result_random->num_rows > 0) {
                    while ($data = $result_random->fetch_assoc()) {
                        echo '<div class="col-md-6 cards">'; 
                            echo '<div class="card card-item mb-6">'; 
                                echo '<img src="' . $data['post_image'] . '" class="card-img" alt="">'; 

                                echo '<div class="card-body">'; 
                                    echo "<a class='card-title text-decoration-none fs-5' href='read.php?post_id=" . $data['post_id'] . "'>" . $data['post_title'] . "</a>"; 
                                    echo '<p class="card-text fs-6">' . shorten_text($data['post_content'], 100) . '</p>'; 
                                echo '</div>'; 

                            echo '</div>'; 
                        echo '</div>'; 
                    }
                }
            echo '</div>'; 


            echo'<div class="col-sm-12 hr-border comment-form">';
            if (isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) {
                // User or admin is logged in
                
                echo "<form class='comment-user mt-5 col-12 ' method='POST' style='margin-bottom: 20px'>"; 
                    echo "<textarea class='comment-box form-control' name='comment_content' placeholder='Bạn nghĩ gì về tin này?' required></textarea>";
                    echo "<br>";
                    echo "<button class='btn btn-success text-white' type='submit' name='comment'>Gửi bình luận</button>";

                    if (isset($_POST['comment'])) {
                        $comment_content = $_POST['comment_content'];
                        $post_id = $_GET['post_id'];
        
                        if (isset($_SESSION['user_id'])) {
                            $user_id = $_SESSION['user_id'];
                            $query = "INSERT INTO Comment (comment_content, user_id, post_id) 
                                    VALUES ('$comment_content', '$user_id', '$post_id')";
                        } else if (isset($_SESSION['admin_id'])) {
                            $admin_id = $_SESSION['admin_id'];
                            $query = "INSERT INTO Comment (comment_content, admin_id, post_id) 
                                    VALUES ('$comment_content', '$admin_id', '$post_id')";
                        }
                        $result = $conn->query($query);
                        if ($result == TRUE) {
                            header("Location: read.php?post_id=$post_id");
                        } else {
                            echo "<div>Error occurred while submitting the comment.</div>";
                        }
                    }
                    } else {
                    echo '<h4 class="mt-3 mb-5 comment-dont-account comment-form hr-border">You have to <a class="text-decoration-none" href="../login/user_login.php">Login</a> to comment !!!</h4>'; 
                echo "</form>";
                
    
                
            }
        echo '</div>'; 

       
        

    
        //Comment Form
        





        // Display comments
        $comments_query = "SELECT Comment.*, User.user_nickname AS user_nickname, Admin.admin_nickname AS admin_nickname
        FROM Comment LEFT JOIN User ON Comment.user_id = User.user_id
        LEFT JOIN Admin ON Comment.admin_id = Admin.admin_id
        WHERE Comment.post_id = '{$post_id}'
        ORDER BY Comment.comment_date DESC";
        $comments_result = $conn->query($comments_query);

        if ($comments_result->num_rows > 0) {
        echo "<div class='container display-comments mt-3 hr-border'>";
        echo "<div class='row'>";
        echo "<div class='col-12 col-lg mx-lg-auto ' >";
        echo "<h3>Bình luận</h3>";

        while ($comment_data = $comments_result->fetch_assoc()) {
        $commentId = $comment_data['comment_id'];

        
        if ($comment_data['user_nickname']) {
            echo '<p class="m-0"><strong><i class="fa-solid fa-user"></i> ' . $comment_data['user_nickname'] . '</strong> - ';
        } else if ($comment_data['admin_nickname']) {
            echo '<p><strong><i class="fa-solid fa-user"></i> ' . $comment_data['admin_nickname'] . '</strong> - ';
        }
        

        
        $comment_date = strtotime($comment_data['comment_date']);
        $current_date = strtotime(date('Y-m-d H:i:s'));
        $time_diff = $current_date - $comment_date;
        $days_diff = floor($time_diff / (60 * 60 * 24));
        $time_ago = ($days_diff > 0) ? $days_diff . " ngày trước" : "Hôm nay";
        echo "<span style='opacity: 0.8;'>" . $time_ago . " lúc " . date('H:i', $comment_date) . "</span></p>";

  
        echo "<div class='comment-container comment-border comment-id-$commentId'>";
        echo "<p>" . $comment_data['comment_content'] . "</p>";
        
        echo "</div>";
        }

        echo "</div>";
        echo "</div>";  
        echo "</div>";
        } 
        echo'</div>';
        echo'</div>';
       
      


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
ob_end_flush()
?>