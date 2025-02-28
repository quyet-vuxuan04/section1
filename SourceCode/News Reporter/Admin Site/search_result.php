
<?php
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

    
}else {
    echo 'You have to Admin login first.'. '<br>';
    echo '<a href="admin_login.php">Login</a>';
}
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

if (isset($_SESSION['search_results'])) {
    $search_results = $_SESSION['search_results'];

    if (!empty($search_results)) {
        foreach ($search_results as $result) {
            echo "<div class='post_container container'>";
                echo "<div class='row post_row'>";
                    echo "<div class='post_card'>";
                        echo "<div class='post_image col-4'>";
                            echo "<img class='post_img' src='" . $result['post_image'] . "' width='100%' style='height: 235px; object-fit: cover;'>";
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

    echo "<div class='container mt-3'>
        <div  class='alert alert-info'>
        No search results found !!!.
        </div>
    </div>";
    
}
?>



<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../Admin Site/admin_css/home.css">
    <link rel="stylesheet" href="../User Site/css/search_result.css">
<head>
    <title>Search Result Page</title>
</head>


</html>