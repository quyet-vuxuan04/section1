<?php
ob_start();
session_start();
include('../connections.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500;600;700&family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../css/content.css">
    <link rel="stylesheet" href="../css/default.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/feedback.css">
    <link rel="stylesheet" href="../css/search_result.css">
</head>

<body>
    <header>
        <div class="container">
            <div class="row navbar">
                <!-- logo -->
                <div class="logo col-6 d-flex align-items-center">
                    <a href="../pages/home.php">
                        <img class="logo-icon"
                            src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/18/Aptech_Limited_Logo.svg/1200px-Aptech_Limited_Logo.svg.png"
                            alt="">
                    </a>
                </div>

                <!-- login (đăng nhập)-->
                <div class="login col-6 d-flex justify-content-end ">
                    <div class="form-search">
                        <form method='POST' class="form">
                            <input class="form-search-input" type="text" name='search'>
                            <button class="form-search-btn" type='submit'>Search</button>
                        </form>
                    </div>
                    <i class="fa-regular fa-user" id="userIcon"></i>
                    <div class="login-options" id="loginOptions">

                        <?php
                        if (isset($_SESSION['user_id']) && isset($_SESSION['user_nickname'])) {
                            $user_id = $_SESSION['user_id'];

                            $query = "SELECT * FROM User WHERE user_id = $user_id";
                            $result = $conn->query($query);

                            if ($result->num_rows > 0) {
                                $data = $result->fetch_assoc();
                                echo "<p class='login-name'>Hello: " . $data['user_nickname'] . "</p>";
                            }
                        }
                        ?>
                    </div>

                    <div class="table-selector" id="tableSelector">
                        <?php
                        //Check if user is logged in or not
                        if (isset($_SESSION['user_id'])) {
                            echo "<a href='../user manager/create.php'>Create a new Post</a>";
                            echo "<a href='../user manager/manager.php'>Manager Posts</a>";
                            echo "<a href='../user manager/user_update.php'>Change Information</a>";
                            echo "<a href='../user manager/change_password.php'>Change Password</a>";
                            echo "<a href='../user manager/view_feedback.php'>View Feedback</a>";
                            echo "<a href='../user manager/user_logout.php'>Logout</a>";
                        } else {
                            echo '<a href="../login/user_login.php">Login</a>';
                            echo '<a href="../loginuser_register.php">Register</a>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

    </header>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var categoryItems = document.querySelectorAll("#category-menu li");

            categoryItems.forEach(function (item) {
                item.addEventListener("click", function () {
                    // Remove 'active' class from all items
                    categoryItems.forEach(function (li) {
                        li.classList.remove("active");
                    });

                    // Add 'active' class to the clicked item
                    this.classList.add("active");
                });
            });
        });
    </script>


<script>
    document.getElementById('userIcon').addEventListener('click', function () {
        var tableSelector = document.getElementById('tableSelector');
        if (tableSelector.style.display === 'none' || tableSelector.style.display === '') {
            tableSelector.style.display = 'block';
        } else {
            tableSelector.style.display = 'none';
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript"src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.umd.min.js"></script>
</body>
</html>

<?php
function search($keyword)
{
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

            header("Location: ../pages/search_result.php");
            exit();
        } else {
            header("Location: ../pages/search_result.php");
            exit();
        }
    } else {
        echo '';
    }
}
ob_end_flush();
?>