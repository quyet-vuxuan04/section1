<?php
include('../User Site/connections.php');
session_start();
$error = ''; // Variable to store the error message

    if (isset($_POST['login'])) {
        $admin_username = $_POST['admin_username'];
        $admin_password = $_POST['admin_password'];
    
        if (empty($admin_username) || empty($admin_password)) {
        $error = "Username and password cannot be blank.";
        }else {
            $query = "SELECT * FROM Admin WHERE admin_username = '$admin_username' AND admin_password = '$admin_password'";
            $result = $conn->query($query);

                if ($result == TRUE && $result->num_rows > 0){
                    $data = $result->fetch_assoc();

                        $_SESSION['admin_id'] = $data['admin_id'];
                        $_SESSION['admin_nickname'] = $data['admin_nickname'];
                        header('Location: home.php');
                        exit();
                    }else{
                        $error = "Username or Password you entered is incorrect. Please try again.";
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
    <link rel="stylesheet" href="../User Site/css/form.css">
    <link rel="stylesheet" href="../Admin Site/admin_css/login.css">
    <title>Login Page</title>
</head>
<body>
<script type="text/javascript"src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <form method='POST'>
        <div class="form-img">
            <img class="form-img-background" src="../User Site/image/background.jpg" alt="">
        </div>
        <div class="form-container">
            <div class="form-box">
                <div class="form-box-left">
                    <div class="form-box-title d-flex justify-content-between">
                        <span>Admin Login</span>
                        
                        <span class="text-end">
                          <a href="home.php" class=" ">
                              <img class="logo-icon" src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/18/Aptech_Limited_Logo.svg/1200px-Aptech_Limited_Logo.svg.png" alt="" width="50%">
                          </a>
                        </span>
                    
                    </div>
                    <div class="form-input form-box-user">
                        <span>USERNAME</span>
                        <input type="text" placeholder="Enter admin's username" class="login" name='admin_username' id="typeEmailX">
                    </div>

                    <div class="form-input form-box-password">
                        <span>PASSWORD</span>
                        <input type="password" placeholder="Enter admin's password" id="typePasswordX" class="login" name='admin_password'/>
                    </div>

                    <button class="form-btn" type="submit" name='login'>
                        Login
                    </button>

                    <!-- <a class="form-forgot" href="recovery_password.php">Forgot Password ?</a> -->
                </div>
                <div class="form-box-right">
                    <div class="form-title-right">
                        <span>Welcome to login</span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>
</html>
