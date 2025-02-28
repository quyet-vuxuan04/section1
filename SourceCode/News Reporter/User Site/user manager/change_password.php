<?php
include('../connections.php');
session_start();
$error = '';
$success = '';
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM User WHERE user_id = '$user_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        if (isset($_POST['change'])) {
            $old_user_password = $_POST['old_password'];
            $new_user_password = $_POST['new_password'];
            $reentered_password = $_POST['reenter_password'];

            // Check if the old password matches the one in the database
            if (password_verify($old_user_password, $data['user_password'])) {
                if ($old_user_password === $new_user_password){
                    $error = "New password cannot be the same as the old password. Please choose a different password.";
                }
                // Check if the new password and the re-entered password match
                elseif ($new_user_password === $reentered_password) {
                    $new_hashed_password = password_hash($new_user_password, PASSWORD_DEFAULT);
                    $query_update = "UPDATE User
                                    SET user_password = '$new_hashed_password'
                                    WHERE user_id = '$user_id'";
                    $result_update = $conn->query($query_update);

                    // Check update result
                    if ($result_update) {
                        $success = "Password changed successful. Click here to return <a href='../pages/home.php'>Home Page</a>.";
                    } else {
                        $error = "Password changed failed. Please try again.";
                    }
                } 
                else {
                    $error = "New password and re-entered password do not match. Please try again.";
                }
            } else {
                $error = "Old password is incorrect. Please try again.";
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
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title>Change Password Page</title>
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
                                <span>Changed Password</span>
                            </div>

                            <div class="form-input form-box-user">
                                <span>ENTER OLD PASSWORD</span>
                                <input type="password" placeholder="Enter old password" id="form3Example1"
                                    class="login text-start" name='old_password' required />
                            </div>



                            <div class="form-input form-box-password">
                                <span>ENTER NEW PASSWORD</span>
                                <input type="password" placeholder="Enter new password" id="form3Example3"
                                    class="login text-start" name='new_password' required />
                            </div>

                            <div class="form-input form-box-password">
                                <span>RE-ENTER NEW PASSWORD</span>
                                <input type="password" placeholder="Re-Enter New Password" id="form3Example1"
                                    class="login text-start" name='reenter_password' required />
                            </div>
                            <button class="form-btn" type="submit" name='change'>Change password</button>

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
} else {
    echo '<script>alert("You have to Login First")</script>';
}
?>