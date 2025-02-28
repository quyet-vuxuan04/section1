<?php
include('../connections.php');
$new_password = '';
$error = '';
$success = '';

if (isset($_POST['reset'])) {
    $user_email = $_POST['user_email'];
    $user_username = $_POST['user_username'];

    $query = "SELECT * FROM User WHERE user_username = '$user_username' AND user_email = '$user_email'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();

        $new_password = random_password();
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the user's hashed password in the database
        $update_query = "UPDATE User SET user_password = '$hashed_password' WHERE user_username = '$user_username' AND user_email = '$user_email'";
        $update_result = $conn->query($update_query);

        if ($update_result) {
            $success = "Reset Password Successful. Your new password: ". $new_password. " Return <a href='../pages/home.php'>Home Page</a>.";   
        }else {
            $error = "Reset Password failed. Please try again !!!";
        }
    } else {
        $error = "Failed to reset password. Invalid username or email... Please try again !!!";
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


function random_password($length = 8)
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $random_strings = '';

    for ($i = 0; $i < $length; $i++) {
        $random_strings .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $random_strings;
}

?>

<!DOCTYPE html>
<html lang="en" class="h-100">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../css/form.css">
    <title>Recover Password Page</title>
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
                    <div class="form-box-title d-flex justify-content-between">
                        <span style="white-space: nowrap;">Reset Password</span>
                        <span class="text-end">
                            <a href="../pages/home.php">
                                <img class="logo-icon" src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/18/Aptech_Limited_Logo.svg/1200px-Aptech_Limited_Logo.svg.png" alt="" width="90%">
                            </a>
                        </span>
                    </div>
                    <div class="form-input form-box-user">
                        <span>USERNAME</span>
                        <input type="text" placeholder="Enter your username" class="login" name='user_username' id="typeUsernameX">
                    </div>

                    <div class="form-input form-box-email">
                        <span>EMAIL</span>
                        <input type="email" placeholder="Enter your email" id="typeEmaildX" class="login" name='user_email'/>
                    </div>

                    <button class="form-btn" type="submit" name='reset'>
                        Reset Password
                    </button>

                </div>
                <div class="form-box-right">
                    <div class="form-title-right">
                        <span>Welcome to Reset Password</span>
                        <a href="user_register.php">Don't have an account ?</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>
</html>




