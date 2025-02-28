<?php
include('../connections.php');
session_start();
$error = ''; // Variable to store the error message
if (isset($_POST['login'])) {
  $user_username = $_POST['user_username'];
  $user_password = $_POST['user_password'];

  if (empty($user_username) || empty($user_password)) {
    $error = "Username and password cannot be blank.";
  } else {
    $query = "SELECT * FROM User WHERE user_username = '$user_username'";
    $result = $conn->query($query);

    if ($result == TRUE && $result->num_rows > 0) {
      $data = $result->fetch_assoc();
      $hashed_password = $data['user_password'];

      // Verify the password
      if (password_verify($user_password, $hashed_password)) {
        $_SESSION['user_id'] = $data['user_id'];
        $_SESSION['user_nickname'] = $data['user_nickname'];
        header('Location: ../pages/home.php');
        exit();
      } else {
        $error = "The password you entered is incorrect. Please try again.";
      }
    } else {
      $error = "The username does not exist in our database. Please check your username or sign up for a new account.";
    }
  }
}

if (!empty($error)) {
  echo '<div class="form-error alert alert-danger alert-dismissible fade show">
    <strong>Error!</strong> ' . $error . '
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>';
}
?>

<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../css/form.css">
    <title>Login Page</title>
</head>
<body>
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
                        <span>Login</span>
                        
                        <span class="text-end">
                          <a href="../pages/home.php" class=" ">
                              <img class="logo-icon" src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/18/Aptech_Limited_Logo.svg/1200px-Aptech_Limited_Logo.svg.png" alt="" width="50%">
                          </a>
                        </span>
                    
                    </div>
                    <div class="form-input form-box-user">
                        <span>USERNAME</span>
                        <input type="text" placeholder="Enter user" class="login" name='user_username' id="typeEmailX">
                    </div>

                    <div class="form-input form-box-password">
                        <span>PASSWORD</span>
                        <input type="password" placeholder="Enter password" id="typePasswordX" class="login" name='user_password'/>
                    </div>

                    <button class="form-btn" type="submit" name='login'>
                        Login
                    </button>

                    <a class="form-forgot" href="recovery_password.php">Forgot Password ?</a>
                </div>
                <div class="form-box-right">
                    <div class="form-title-right">
                        <span>Welcome to login</span>
                        <a href="user_register.php">Don't have an account ?</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>
</html>
