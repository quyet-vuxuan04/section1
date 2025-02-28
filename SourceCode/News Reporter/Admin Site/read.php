<?php
ob_start();
include('../User Site/connections.php');
session_start();

if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_nickname'])){
    $admin_id = $_SESSION['admin_id'];

    $query = "SELECT * FROM Admin WHERE admin_id = $admin_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    }
    echo '<div class="container">
        <div class="row" >
            <div class="col-xl-3 p-0 hr">
                <a href="home.php" class="">
                    <img class="" src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/18/Aptech_Limited_Logo.svg/1200px-Aptech_Limited_Logo.svg.png" alt="" width="40%">
                </a>
            </div>
        

            <div class="col-xl-8 p-0 hr d-flex d-flex justify-content-end align-items-center">
                <div class="form-search ">
                    <form method="POST" class="form m-0 me-3">
                        <input class="form-search-input" type="text" name="search">
                        <button class="form-search-btn" type="submit">Search</button>
                    </form>
                </div>
                <span class="fs-4 fw-semibold"> Hello Admin: ' . $data['admin_nickname'] . ' </span>
            </div>

            

            <div class="text-end col-1 p-0 hr d-flex justify-content-center align-items-center">
                <a class="btn btn-danger " href="admin_logout.php">Logout</a>
            </div>
        </div>

       

        <div class="col-md-6 mt-3 d-flex p-0">
            
            <a href="home.php" class="btn btn-primary text-white fs-5">Home</a>
        </div>
    </div>';
    
function search($keyword) {
    global $conn;
    // Sanitize the keyword to prevent SQL injection
    $keyword = mysqli_real_escape_string($conn, $keyword);

    $query_search = "SELECT * FROM Post WHERE status = '1'";

    if (!empty($keyword)) {
        // Add conditions to search only if the keyword is not empty
        $query_search .= " AND (post_content LIKE '%$keyword%' OR post_title LIKE '%$keyword%')";
    }

    $result_search = $conn->query($query_search);

    // Check if any results were found
    if ($result_search->num_rows > 0) {
        $results = array(); // Create an empty array to store the results

        while ($row = $result_search->fetch_assoc()) {
            $results[] = $row; // Add each row to the results array
        }
        return $results; // Return the array of results
    } else {
        return array(); // Return an empty array if no results found
    }
}

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $search_result = search($search);

    if (!empty($search)) {
        // Check if the search keyword is not empty
        if (!empty($search_result)) {
            // Store the search results in a session variable
            
            $_SESSION['search_results'] = $search_result;

            header("Location: search_result.php");
            exit();
        } else {
            header("Location: search_result.php");
            exit();
        }
    } else {
        echo '';
    }
}
}else {
    echo 'You have to Admin login first.'. '<br>';
    echo '<a href="admin_login.php">Login</a>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>read Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../Admin Site/admin_css/read.css">
</head>
<body>

<?php
if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
    $query = "SELECT *
          FROM Post
          INNER JOIN Category ON Post.category_id = Category.category_id
          LEFT JOIN Admin ON Post.admin_id = Admin.admin_id
          LEFT JOIN User ON Post.user_id = User.user_id
          WHERE Post.post_id = '{$post_id}'";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        // content 
        echo "<div class='container content'>";
        echo "<div class='row content-row'>";
        echo "<div class='content-item mt-3'>";
        echo "<h2 class='content-title fs-2 fw-semibold'>" . $data['post_title'] . "</h2>"; // Title displayed
        // Display the nickname if it exists
        echo !empty($data['admin_id']) ?
            "<p class='content-admin Title-Name Title-AdminName fs-5 text-black-50'>" . $data['admin_nickname'] . "</p>" :
            "<p class='content-user fs-5 Title-Name Title-UserName fs-5 text-black-50'> " . $data['user_nickname'] . "</p>";
        echo "<p class='content-time text-black-50 fs-5 Title-Time Title-Date'>" . date('l, d/m/Y - H:i', strtotime($data['upload_date'])) . "</p>"; // Date displayed
        echo "<img class='content-IMG'src='" . $data['post_image'] . "' alt='' >"; // Image displayed

        // Split the content into paragraphs
        $paragraphs = explode("\n", $data['post_content']);

        // Display each paragraph
        foreach ($paragraphs as $paragraph) {
            echo "<p class='content-post fs-5 fw-light mt-6'>" . $paragraph . "</p>";
        }
        echo "</div>";
        echo "</div>";
        echo "</div>";

        // Display comments
        $comments_query = "SELECT Comment.*, User.user_nickname AS user_nickname, Admin.admin_nickname AS admin_nickname
                   FROM Comment LEFT JOIN User ON Comment.user_id = User.user_id
                   LEFT JOIN Admin ON Comment.admin_id = Admin.admin_id
                   WHERE Comment.post_id = '{$post_id}'
                   ORDER BY Comment.comment_date DESC";
        $comments_result = $conn->query($comments_query);
        if ($comments_result->num_rows > 0) {
            echo "<div class='container'>";
            echo "<div class='row'>";
            echo "<div class='col-md-4'>";
            echo "<h3>Comments</h3>";
            while ($comment_data = $comments_result->fetch_assoc()) {
                echo "<p>" . $comment_data['comment_content'] . "</p>";
                
                if ($comment_data['user_nickname']) {
                    echo "<p>Posted by: " . $comment_data['user_nickname'] . "</p>";
                } else if ($comment_data['admin_nickname']) {
                    echo "<p>Posted by: " . $comment_data['admin_nickname'] . "</p>";
                }
                
                $comment_date = strtotime($comment_data['comment_date']);
                $current_date = strtotime(date('Y-m-d H:i:s'));
                $time_diff = $current_date - $comment_date;
                $days_diff = floor($time_diff / (60 * 60 * 24));
                $time_ago = ($days_diff > 0) ? $days_diff . " days ago" : "Today";
                echo "<p>Date: " . $time_ago . " at " . date('H:i', $comment_date) . "</p>";
            }
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }

        // Comment form
        if (isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) {
            // User or admin is logged in
            echo "<div class='container mb-3'>";
            echo "<div class='row'>";
            echo "<form class='comment-user' method='POST'>"; // Submit the form to the current page
            echo "<textarea class='comment-box mb-3 p-3' name='comment_content' placeholder='Enter your comment' required></textarea>";
            echo "<br>";
            echo "<button class='btn btn-success text-white' type='submit' name='comment'>Comment</button>";
            echo "</form>";
            echo "</div>";
            echo "</div>";

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
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
            echo '<p>You have to login to comment !!!</p>';
        }

}
}
ob_end_flush();
?>

