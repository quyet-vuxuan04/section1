<?php
include('../connections.php');
$error = '';
$success = '';

function check_username($conn, $username){
    $query = "SELECT user_username FROM User WHERE user_username = '$username'";
    $result = $conn->query($query);
    return $result->num_rows > 0;
}

function check_email($conn, $email){
    $query = "SELECT user_email FROM User WHERE user_email = '$email'";
    $result = $conn->query($query);
    return $result->num_rows > 0;
}

function check_nickname($conn, $nickname){
    $query = "SELECT user_nickname FROM User WHERE user_nickname = '$nickname'";
    $result = $conn->query($query);
    return $result->num_rows > 0;
}

if (isset($_POST['register'])) {
    $user_username = $_POST['user_username'];
    $user_password = $_POST['user_password'];
    $user_email = $_POST['user_email'];
    $user_nickname = $_POST['user_nickname'];

    // Check if username, email, or nickname already exists
    if (check_username($conn, $user_username)) {
        $error = "Username already exists";
    } elseif (check_email($conn, $user_email)) {
        $error = "Email already exists";
    } elseif (check_nickname($conn, $user_nickname)) {
        $error = "Nickname already exists";
    } else {
        // Hash the password
        $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

        $query = "INSERT INTO User (user_username, user_password, user_email, user_nickname)
                  VALUES ('$user_username', '$hashed_password', '$user_email', '$user_nickname')";
        $result = $conn->query($query);

        if ($result !== false) {
            $success = "Registration successful. Click here to <a href='user_login.php'>login</a>.";
        } else {
            $error = "Registration failed. Please try again.";
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
<html lang="en" class="h-100">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register Page</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/form.css">
</head>
<body>
    <!-- MDB -->
    <script type="text/javascript"src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <form class="form" method='POST' >
        <div class="form-img">
            <img class="form-img-background" src="../image/background.jpg" alt="">
        </div>
        <div class="form-container">
            <div class="form-box">
                <div class="form-box-left">
                    <div class="form-box-title d-flex justify-content-between">
                        <span>Register</span>
                        <span class="text-end">
                            <a href="../pages/home.php">
                                <img class="logo-icon" src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/18/Aptech_Limited_Logo.svg/1200px-Aptech_Limited_Logo.svg.png" alt="" width="50%">
                            </a>
                        </span>
                    </div>
                    <div class="form-input">
                        <span>USERNAME</span>
                        <input type="text" placeholder="Enter user" class="" name='user_username' id="form3Example1" required />
                    </div>
                    <div class="form-input">
                        <span>PASSWORD</span>
                        <input type="password" placeholder="Enter password" class="" name='user_password' id="form3Example4" required />
                        <span>RE-PASSWORD</span>
                        <input type="password" placeholder="Enter re-password" class="" name='user_password' id="typePasswordX"/>
                    </div>

                    <div class="form-input">
                        <span>EMAIL</span>
                        <input type="email" placeholder="Enter email" class="" name='user_email' id="form3Example3" required />
                    </div>
                    <div class="form-input">
                        <span>NICKNAME</span>
                        <input type="text" placeholder="Enter nickname" class="" name='user_nickname' id="form3Example2" required />
                    </div>

                    <button class="form-btn" type="submit" name='register'>
                        Register
                    </button>
                </div>
                <div class="form-box-right">
                    <div class="form-title-right">
                        <span>Welcome to register</span>
                        <a href="user_login.php">Are you logged in?</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>
</html>