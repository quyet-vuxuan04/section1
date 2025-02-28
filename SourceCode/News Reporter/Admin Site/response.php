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
    <a href="home.php" class="btn btn-secondary">HOME</a>
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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../Admin Site/admin_css/home.css">
    <link rel="stylesheet" href="../Admin Site/admin_css/response.css">
    <title>Response Admin</title>
</head>
<body>
<?php
$record_per_page = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $record_per_page;

// Retrieve approved records from the database (Truy xuất các bản ghi đã được phê duyệt từ cơ sở dữ liệu)
$query = "SELECT * FROM Feedback, User WHERE Feedback.user_id = User.user_id";
$result = $conn->query($query);

// Calculate the total number of approved posts (Tính tổng số bài viết được phê duyệt)
$data = $result->num_rows;

//  Calculate the total number of pages based on the number of approved posts and the records per page (
$total_pages = ceil($data / $record_per_page);

if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $query = "SELECT * FROM Feedback, User WHERE Feedback.user_id = User.user_id LIMIT $offset, $record_per_page";
    $result = $conn->query($query);
   
    if ($result->num_rows > 0) {
        while ($data = $result->fetch_assoc()) {
            $fb_id = $data['fb_id'];
            ?>
            <div class="container mt-3" style="border:1px solid #ccc; padding:20px;margin-bottom:30px !important">
                <h2>Feedback of <?php echo $data['user_nickname']; ?></h2>
                <h3>Username: <?php echo $data['user_username']; ?></h3>
                <p>Title: <?php echo $data['fb_title']; ?></p>
                <p>Content: <?php echo $data['fb_content']; ?></p>
                <?php
                $response_query = "SELECT * FROM Response, Feedback
                                   WHERE Response.fb_id = Feedback.fb_id AND Feedback.fb_id = '$fb_id'
                                   ";
                $response_result = $conn->query($response_query);
                if ($response_result == false) {
                    echo "Error: " . $conn->error;
                }

                if ($response_result->num_rows > 0) {
                    $response_data = $response_result->fetch_assoc();
                    ?>
                    <h3>Response</h3>
                    <p>Response Title: <?php echo $response_data['response_title']; ?></p>
                    <p>Response Content: <?php echo $response_data['response_content']; ?></p>
                    <?php
                } else {
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['fb_id'] == $fb_id) {
                        $response_title = $_POST['response_title'];
                        $response_content = $_POST['response_content'];

                        $query_response = "INSERT INTO Response (response_title, response_content, admin_id, fb_id)
                                           VALUES ('$response_title', '$response_content', '$admin_id', '$fb_id')";
                        $result_response = $conn->query($query_response);

                        if ($result_response == TRUE) {
                            echo '<div class="alert alert-success" role="alert">Response Successfully</div>';
                        } else {
                            echo '<div class="alert alert-danger" role="alert">Response Failed</div>';
                        }
                    } else {
                        ?>
                        <form method="POST">
                            <h3>Response</h3>
                            <div class="form-group">
                                <label>Response Title</label>
                                <input type="text" class="form-control" name="response_title">
                            </div>
                            <div class="form-group">
                                <label>Response Content</label>
                                <textarea class="form-control" name="response_content" cols="30"
                                          rows="10"></textarea>
                            </div>
                            <input type="hidden" name="fb_id" value="<?php echo $fb_id; ?>">
                            <button type="submit" class="btn btn-primary">Response</button>
                        </form>
                        <?php
                    }
                }
                ?>
            </div>
            <?php
        }
    }else{
        echo 'Not found yet';
    }
} else {
    echo '<div class="container mt-3 alert alert-warning" role="alert">You have to login first !!!</div>';
}

//Paginations links
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

?>
<!-- Add Bootstrap JS and Popper.js scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
