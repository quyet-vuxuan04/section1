<?php
// Connect to the database
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

    <nav class="navbar pl-1 mt-3 navbar-expand-lg col-xl-2">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <select class="form-select fs-5 py-0" onchange="location = this.value;">
                <option value="home.php">Home</option>
                <option value="business.php">Business</option>
                <option value="technology.php">Technology</option>
                <option value="sports.php">Sports</option>
                <option value="beauty.php" selected>Beauty</option>
                <option value="society.php">Society</option>
                <option value="todayinworld.php">Today in World</option>
            </select>
        </div>
    </nav>

    <div class="col-md-6 mt-3 d-flex p-0">
            <a class="btn btn-info text-white fs-5" href="create.php">Create a new Post</a>
            <a class="btn btn-secondary text-white fs-5 ms-3"  href="response.php">Response</a>
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

//Shorten text for display content
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
// Pagination 
$record_per_page = 6;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $record_per_page;

// Retrieve approved records from the database
$query = "SELECT * FROM Post, Category WHERE Post.category_id = Category.category_id AND Category.category_name = 'Beauty'";
$result = $conn->query($query);

// Calculate the total number of approved posts
$total_approved_posts = $result->num_rows;

// Calculate the total number of pages based on the number of approved posts and the records per page
$total_pages = ceil($total_approved_posts / $record_per_page);

// Retrieve records for the current page
$query = "SELECT * FROM Post, Category WHERE Post.category_id = Category.category_id AND Category.category_name = 'Beauty'
          LIMIT $offset, $record_per_page";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<div class='container mt-3'>";
        echo "<div class='row'>";
            while ($data = $result->fetch_assoc()) {
                echo "<div class='col-md-4 mb-4'>";
                    echo "<div class='card rounded-3'>";
                        echo "<div>";
                            echo "<img src='" . $data['post_image'] . "' class='card-top-img rounded-top' alt='Post Image'>"; //Image displayed
                        echo "</div>";
                        echo "<div class='card-body'>";
                            echo "<a class='card-title text-decoration-none fs-3 fw-semibold ' href='read.php?post_id=" . $data['post_id'] . "'>" . $data['post_title'] . "</a>"; //Title display
                            echo "<p class='card-text fs-6 text-body-secondary'>" . $data['post_content'] . "</p>"; //Content displayed

                            echo "<div class='d-flex justify-content-between align-items-center'>";
                                echo "<p class='card-category fs-5 fw-semibold text-black mb-0'>" . $data['category_name'] . "</p>"; //Category name
                                echo "<p class='card-rating fs-5 fw-semibold text-black mb-0'>Rating: " . $data['rate'] . "</p>"; //Rating
                            echo "</div>";

                            if ($data['status'] == 0) {
                                echo '<span class="mt-3 card-status fs-6 align-items-center badge bg-warning">Status: Pending</span>';
                            } else {
                                $status_label = ($data['status'] == 1) ? 'Approved' : 'Rejected';
                                echo '<div class="mt-3 d-flex justify-content-start align-items-center">';
                                    echo '<span class=" card-status fs-6 align-items-center rounded-3 badge bg-success">' . $status_label . '</span>';
                                    echo "<a href='delete.php?post_id=" . $data['post_id'] . "' class='card-delete btn btn-danger rounded-3 fs-6 btn-sm ms-2'>Delete</a>";
                                echo '</div>';
                            }

                            if ($data['status'] == 0) {
                                echo '<form method="POST" action="update_status.php" class="mt-3">';
                                    echo '<input type="hidden" name="post_id" value="' . $data['post_id'] . '">';
                                    echo '<button type="submit" name="status" value="approve" class="btn btn-success btn-sm">Approve</button>';
                                    echo '<button type="submit" name="status" value="reject" class="btn btn-danger btn-sm ms-2">Reject</button>';
                                echo '</form>';
                            }

                            if (isset($_SESSION['admin_id'])) {
                                if ($_SESSION['admin_id'] == $data['admin_id']){
                                    echo "<a href='update.php?post_id=" . $data['post_id'] . "'>" . "Update</a>";
                                }
                            }

                            $currentPage = isset($_GET['page']) ? $_GET['page'] : '1';
                            // Rating form
                            echo "<form method='POST' action='business.php?post_id=" . $data['post_id'] . "&page=$currentPage' class='mt-2'>";
                                echo "<div class='input-group'>";
                                    echo "<select class='form-select fs-5 fw-semibold' name='post_rating'>";
                                    echo "<option>--- Rating ---</option>";
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo "<option value='$i'>$i</option>";
                                    }
                                    echo "</select>";
                                    echo "<button type='submit' name='rate_submit' class='btn btn-primary'>Rate</button>";
                                echo "</div>";
                            echo "</form>";

                        echo "</div>"; // card-body
                    echo "</div>"; // card
                echo "</div>"; // col-md-4
            }
        echo "</div>"; // row
    echo "</div>"; // container

   // Pagination links
    echo "<div class='container'>";
        echo "<div class='row'>";
            echo "<div class='col-md-12 mb-3'>";
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
  echo 'Error: No data found !';
}

// Handle rating submission
if (isset($_POST['rate_submit'])) {
    $post_id = $_GET['post_id'];
    $post_rating = $_POST['post_rating'];

    // Update the rating of the post in the database
    $query = "UPDATE Post SET rate = $post_rating WHERE post_id = $post_id";
    $result = $conn->query($query);

    if ($result == TRUE) {
        echo 'Post rated successfully';
        header ('Location: home.php');
    } else {
        echo 'Failed to rate the post: ' . $conn->error;
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
    <title>Beauty Admin</title>
</head>
</html>