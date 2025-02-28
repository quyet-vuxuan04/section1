<?php
// Connect to the database
include('../User Site/connections.php');
$message = ""; // Biến để lưu trữ thông báo
session_start();
if (isset($_POST['submit'])) {
    $post_title = $_POST['post_title'];
    $post_content = $_POST['post_content'];
    $post_image = $_POST['post_image'];
    $post_category = $_POST['post_category'];

    // Đảm bảo các giá trị không rỗng trước khi thêm vào cơ sở dữ liệu
    if (!empty($post_title) && !empty($post_content) && !empty($post_image) && !empty($post_category)) {

        // Sử dụng Prepared Statements để tránh SQL Injection
        $stmt = $conn->prepare("INSERT INTO post (post_title, post_content, post_image, category_id) VALUES (?, ?, ?, ?)");

        // Kiểm tra xem prepare có thành công không
        if ($stmt) {
            $stmt->bind_param("sssi", $post_title, $post_content, $post_image, $post_category);

            // Thực hiện truy vấn và kiểm tra kết quả
            if ($stmt->execute()) {
                $message = "Post created successfully!";
            } else {
                $message = "Error executing query: " . $stmt->error;
            }

            // Đóng Prepared Statement
            $stmt->close();
        } else {
            $message = "Error preparing query: " . $conn->error;
        }
    } else {
        $message = "All fields are required!";
    }
}
if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_nickname'])){
    $admin_id = $_SESSION['admin_id'];

    $query = "SELECT * FROM Admin WHERE admin_id = $admin_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    }
    echo '<div class="container">
        <div class="row">
            <div class="col-xl-6 p-0 hr">
                <a href="home.php" class="">
                    <img class="" src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/18/Aptech_Limited_Logo.svg/1200px-Aptech_Limited_Logo.svg.png" alt="" width="30%">
                </a>
            </div>

            <div class="col-xl-5 p-0 hr d-flex justify-content-end align-items-center">
                <span class="fs-4 fw-semibold"> Hello: ' . $data['admin_nickname'] . ' </span>
            </div>

            <div class="text-end col-1 p-0 hr d-flex justify-content-center align-items-center">
                <a class="btn btn-danger" href="admin_logout.php">Logout</a>
            </div>
        </div>
    </div>';
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Create new post Admin</title>
    <link rel="stylesheet" href="../Admin Site/admin_css/create.css">
</head>
<body> 
    

<div class="container my-5">
    <div class="row">
    <form method="POST" class="form">
        <p class="my-3 fs-2 fw-bolder">Create a new Post</p>
        <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo ($message == "Post created successfully!") ? 'success' : 'danger'; ?> mb-3">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>
            <div class="my-3 form-box form-title">
                <label for="post_title" class="form-label fs-5 fw-medium">Title</label>
                <input type="text" placeholder="Enter title" class="form-control" id="post_title" name="post_title" required>
            </div>
            <div class="mb-3 form-box form-category">
                <label for="post_category" class="form-label fs-5 fw-medium">Category</label>
                <select class="form-select" id="post_category" name="post_category" required>
                    <option value="">---Select a category---</option>
                    <?php
                    $categories_query = "SELECT category_id, category_name FROM category";
                    $categories_result = $conn->query($categories_query);

                    if ($categories_result->num_rows > 0) {
                        while ($row = $categories_result->fetch_assoc()) {
                            $category_id = $row['category_id'];
                            $category_name = $row['category_name'];
                            echo "<option value='$category_id'>$category_name</option>";
                        }
                    }
                    $conn->close();
                ?>
                </select>
            </div>
            <div class="mb-3 form-box form-image">
                <label for="post_image" class="form-label fs-5 fw-medium">Image link</label>
                <input type="text" class="form-control" placeholder="Enter link" id="post_image" name="post_image" required>
            </div>
            <div class="mb-3 form-box form-content">
                <label for="post_content" class="form-label fs-5 fw-medium">Content</label>
                <textarea class="form-control " placeholder="Enter content" id="post_content" name="post_content" cols="50" rows="5" required></textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-primary mb-3">CREATE POST</button>
            <a class="btn btn-danger mb-3" href="home.php">BACK HOME</a>
        </form>
    </div>
</div>
</body>
</html>
