<?php
include('user_header.php');
$error = '';
$success = '';
// Create a new Post
if (isset($_SESSION['user_id'])) {
    if (isset($_POST['submit'])) {
        $post_title = $_POST['post_title'];
        $post_content = $_POST['post_content'];
        $post_image = $_POST['post_image'];
        $post_category = $_POST['post_category'];
        $user_id = $_SESSION['user_id'];

        $query = "INSERT INTO Post (post_title, post_content, post_image, category_id, user_id)
                  VALUES ('$post_title', '$post_content', '$post_image', '$post_category', '$user_id')";
        $result = $conn->query($query);

        if ($result == TRUE) {
            $success = "Thanks for creating a new post. Your new post will be sent to the Admin for approval. Please be patient and wait !!!";
        } else {
            $error = "Your creating was failed. Please try again !!!";
        }
    }
}

if (!empty($error)) {
    echo '<div class="form-error alert alert-danger alert-dismissible fade show">
    <strong>Error!</strong> ' . $error . '
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>';
}

if (!empty($success)) {
    echo '<div class="form-error alert alert-success alert-dismissible fade show">
    <strong>Success!</strong> ' . $success . '
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <link rel="stylesheet" href="../css/create.css">
    <title>Create A New Post Page</title>
</head>
<body>
    <!-- category (thể loại)-->
    <div class="category-list">
            <ul class="list-unstyled list-inline">
                <li><a href="../pages/home.php"><i class="icon-home fa-solid fa-house"></i></a></li>
                <li class="list-inline-item"><a  href="../pages/business.php">Business</a></li>
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
<form method='POST'>
    <div class="container form">
        <div class="row form-create">
            <div class="col-6 form-card">
                <div class="form-header mb-3">
                    <span class="fs-2">Create a New Post</span>
                    <p class="fs-4">Please enter your Post information below !!!</p>
                </div>
                <div class="form-body">
                    <input type="text" placeholder="Enter Post title here" id="form3Example1" class="form-body-title" name="post_title" required />
                    <div class="form-select-category mb-3">
                        <select name="post_category" class="mb-3">
                            <option>---Select a category---</option>
                            <?php
                                $categories_query = "SELECT category_id, category_name FROM Category";
                                $categories_result = $conn->query($categories_query);
                                if ($categories_result->num_rows > 0) {
                                    while ($row = $categories_result->fetch_assoc()) {
                                    $category_id = $row['category_id'];
                                        $category_name = $row['category_name'];
                                        echo "<option class='category-id' value='$category_id'>$category_name</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>

                    <div class="form-up-image mb-3 ">
                        <span id="displayFileName"></span>
                        <input type="text" placeholder="Enter link image" id="uploadImage" class="mb-2" name="post_image"  required />
                        <label for="uploadImage" class="">
                            <i class="fas fa-upload" >
                            </i>
                            <span>Image Link</span>
                        </label>
                    </div>
                    
                    <button class="form-btn " type="submit" name='submit'>Create</button>

                </div>
            </div>
            <!-- <div class="col-2"></div> -->
            <div class="col-6">
                <div class="form-text-area">                      
                    <textarea placeholder="Place post's content here" name="post_content" id="form3Example4" cols="50" rows="10" required></textarea>         
                </div>
                
            </div>
        </div>
    </div>
</form>

<?php 
include('user_footer.php');
?>