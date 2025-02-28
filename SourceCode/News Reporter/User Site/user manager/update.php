<?php
include('user_header.php');
$error = '';
$success = '';
if (isset($_SESSION['user_id'])){
    $post_id = $_GET['post_id'];
    $query = "SELECT * FROM Post 
               JOIN Category ON Post.category_id = Category.category_id 
               JOIN User ON User.user_id = Post.user_id
               WHERE post_id = $post_id";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0){
        $data = $result->fetch_assoc();
        if (isset($_POST['submit'])){
            $new_post_title = $_POST['post_title'];
            $new_post_content = $_POST['post_content'];
            $new_post_image = $_POST['post_image'];
            $new_post_category = $_POST['post_category'];
            
            $query_update = "UPDATE Post
                            JOIN Category ON Post.category_id = Category.category_id 
                            JOIN User ON User.user_id = Post.user_id
                            SET Post.post_title = '$new_post_title', 
                                Post.post_content = '$new_post_content',
                                Post.post_image = '$new_post_image',
                                Post.category_id = '$new_post_category'   
                            WHERE post_id = $post_id";
            $result_update = $conn->query($query_update);

            //Check update result
            if ($result_update) {
                $success = "Your Updated information is successfully!. Click <a class='form-hover' href='manager.php'>here</a> to return Manage Page.";
            } else {
                $error = "Your Updated was failed. Please try again !!!";
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
<link rel="stylesheet" href="../css/manager.css">
    <title>Update Post Page</title>
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


    <form method='POST'>
        <div class="container my-5">
            <div class="row">
                <div class="col-12">
                    <div class="card feedback-card rounded-end-circle bg-light-75">
                        <div class="card-body">
                            <div class="mb-md-5 mt-md-4">
                                <p class="fw-bold fs-2 mb-2 text-uppercase">Update Post</p>
                                

                                <div class="title mb-3">
                                    <label class="title-process mb-3 fs-5">Title</label>
                                    <input type="text" name="post_title" value='<?php echo isset($data['post_title']) ? $data['post_title'] : ''; ?>' required>
                                </div>

                                <div class="mb-3">
                                    <select name="post_category">
                                    <?php
                                    $categories_query = "SELECT category_id, category_name FROM Category";
                                    $categories_result = $conn->query($categories_query);

                                    if ($categories_result->num_rows > 0){
                                        while ($row = $categories_result->fetch_assoc()){
                                            $category_id = $row['category_id'];
                                            $category_name = $row['category_name'];
                                            // Check if the current category is the selected category
                                            $selected = ($category_id == $data['category_id']) ? 'selected' : '';
                                            echo "<option value='$category_id' $selected>$category_name</option>";
                                        }
                                    }
                                    ?>
                                    </select>
                                </div>

                                <div class="content mb-3">
                                    <label class="title-process mb-3 fs-5">Content</label>
                                    <textarea class="content-process" name="post_content" cols="50" rows="10" required><?php echo isset($data['post_content']) ? $data['post_content'] : ''; ?></textarea>
                                </div>

                                <div class="update-img">
                                    <label class="title-process mb-3 fs-5">Image</label>
                                    <img class=" mb-3"  src="<?php echo isset($data['post_image']) ? $data['post_image'] : ''; ?>" width="20%">
                                    <input class=" mb-3" type="text" name="post_image" value="<?php echo isset($data['post_image']) ? $data['post_image'] : ''; ?>" required>
                                </div>
                                
                                <button class="login-btn btn-manager-process"  type='submit' name='submit'>Update</button>
                               <button class="login-btn btn-manager-process">
                                <a href="manager.php" style='text-decoration: none; color: #156fc9b7'>Cancel</a>
                               </button>                                    
                            </div>
                        </div>
                    </div>
                </div>
                
                
            </div>
        </div>

    </form>

    

<?php
    }
} else {
    echo '<p class="error-message">Bạn phải đăng nhập trước !!!</p>';
}
include("user_footer.php");
?>
