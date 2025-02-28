<?php
include('../User Site/connections.php');
session_start();
if (isset($_SESSION['admin_id'])){
    $post_id = $_GET['post_id'];
    $query = "SELECT * FROM Post 
                       JOIN Category ON Post.category_id = Category.category_id 
                       WHERE post_id = '$post_id'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0){
        $data = $result->fetch_assoc();
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $new_post_title = $_POST['post_title'];
            $new_post_content = $_POST['post_content'];
            $new_post_image = $_POST['post_image'];
            $new_post_category = $_POST['post_category'];
            
            $query_update = "UPDATE Post
                            JOIN Category ON Post.category_id = Category.category_id 

                            SET Post.post_title = '$new_post_title', 
                                Post.post_content = '$new_post_content',
                                Post.post_image = '$new_post_image',
                                Post.category_id = '$new_post_category'   
                            WHERE post_id = '$post_id'";
            $result_update = $conn->query($query_update);

            //Check update result
            if ($result_update) {
                $update_message = "Updated successfully";
            } else {
                $update_message = "Update failed: " . $conn->error;
            }
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Post</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <a class="btn btn-info" href="home.php">Home</a>
    <form method='POST'>
        <h2>Update Post</h2>
        <?php if (!empty($update_message)): ?>
        <div class="alert alert-<?php echo ($update_message == "Updated successfully") ? 'success' : 'danger'; ?> mb-3">
            <?php echo $update_message; ?>
        </div>
    <?php endif; ?>
        <div class="form-group">
            <label for="post_title">Title</label>
            <input type="text" class="form-control" name="post_title" value='<?php echo $data['post_title']; ?>'>
        </div>
        <div class="form-group">
            <label for="post_content">Content</label>
            <textarea class="form-control" name="post_content" cols="50" rows="10"><?php echo $data['post_content']; ?></textarea>
        </div>
        <div class="form-group">
            <label for="post_image">Image</label>
            <img src="<?php echo $data['post_image']; ?>" class="img-fluid" alt="Post Image" width="20%">
            <input type="text" class="form-control" name="post_image" value="<?php echo $data['post_image']; ?>">
        </div>
        <div class="form-group">
            <label for="post_category">Category</label>
            <select class="form-control" name="post_category">
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
        <button type='submit' class="btn btn-primary">Update</button>
    </form>
</body>
</html>

<?php
    }else {
        echo 'Update failed';
    }

}else {
    echo 'You have to Login first !!!'. '<br>';
}
?>

