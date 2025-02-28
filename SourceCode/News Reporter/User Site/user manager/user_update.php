<?php
include('../connections.php');
session_start();
$error = '';
$success = '';
if (isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM User WHERE user_id = '$user_id'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0){
        $data = $result->fetch_assoc();
    
        if (isset($_POST['update'])){
            $new_user_username = $_POST['user_username'];
            $new_user_email = $_POST['user_email'];
            $new_user_nickname = $_POST['user_nickname'];
            
            $query_update = "UPDATE User
                            SET user_username = '$new_user_username', 
                                user_email = '$new_user_email',
                                user_nickname = '$new_user_nickname'  
                            WHERE user_id = '$user_id'";
            $result_update = $conn->query($query_update);

            //Check update result
            if ($result_update) {
                $success = "Your Updated information is successfully!. Click <a class='form-hover' href='../pages/home.php'>here</a> to return Home Page.";
            } else {
                $error = "Your Updated was failed. Please try again !!!";
            }
        }  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Change User Information Page</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/form.css">
</head>
<body>
    <!-- MDB -->
    <script type="text/javascript"src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<form class="form" method='POST'>
        <div class="form-img">
            <img class="form-img-background" src="../image/background.jpg" alt="">
        </div>
        <div class="form-container">
            <div class="form-box">
                <div class="form-box-left">
                    <div class="form-box-title">
                        <span>Changed User Information</span>
                    </div>


                    
                    <div class="form-input form-box-user">
                        <span>USERNAME</span>
                        <input type="text" placeholder="Enter user" id="form3Example1" class="" name='user_username' value='<?php echo $data['user_username']; ?>' required/>
                       
                    </div>


                    
                    <div class="form-input form-box-email">
                        <span>EMAIL</span>
                        <input type="email" placeholder="Enter email" id="form3Example3" class="" name='user_email' value='<?php echo $data['user_email'];?>' required/>
                    </div>

                    <div class="form-input form-box-nickname">
                        <span>NICKNAME</span>
                        <input type="text" placeholder="Enter nickname" id="form3Example1" class="login text-start" name='user_nickname' value='<?php echo $data['user_nickname'];?>' required/>
                    </div>
                    <button class="form-btn" type="submit" name='update'>
                        Change Information
                    </button>
                </div>
                <div class="form-box-right">
                    <div class="form-title-right">
                        <a href="../pages/home.php"><span>Back to Home Page</span></a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>
</html>

<?php
    }
}else {
    echo '<script>alert("You have to Login First")</script>';
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
