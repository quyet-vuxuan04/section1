<?php
include('../header.php');

$error = '';
$success = '';

// Check if the user is logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['user_nickname'])) {
    $user_id = $_SESSION['user_id'];

    $query = "SELECT * FROM User WHERE  user_id = $user_id";
    $result = $conn->query($query);

    if ($result == TRUE && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
        
        if (isset($_POST['submit'])) {
            // Process the form submission only if the user is logged in
            $fb_title = $_POST['fb_title'];
            $fb_content = $_POST['fb_content'];
            $user_id = $_SESSION['user_id'];

            $query_feedback = "INSERT INTO Feedback (fb_title, fb_content, user_id)
                               VALUES ('$fb_title', '$fb_content', '$user_id')";
            $result_feedback = $conn->query($query_feedback);

            if ($result_feedback !== false) {
                $success = "Feedback successful.";
            } else {
                $error = "Feedback failed. Please try again.";
            }
        }
    } else {
        // User is not logged in
        $error = "You must be logged in to submit feedback.";
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
    <link rel="stylesheet" href="../css/contact_us.css">
    <link rel="stylesheet" href="../css/feedback.css">
    <link rel="stylesheet" href="../css/content.css">
    <title>Contact Us Page</title>
</head>
<body>
    
        <!-- category (thể loại)-->
        <div class="category-list">
            <ul class="list-unstyled list-inline">
                <li><a href="home.php"><i class="icon-home fa-solid fa-house"></i></a></li>
                <li class="list-inline-item"><a href="business.php">Business</a></li>
                <li class="list-inline-item"><a href="technology.php">Technology</a></li>
                <li class="list-inline-item"><a href="sports.php">Sports</a></li>
                <li class="list-inline-item"><a href="beauty.php">Beauty</a></li>
                <li class="list-inline-item"><a href="sociaty.php">Sociaty</a></li>
                <li class="list-inline-item"><a href="todayinworld.php">Today in World</a></li>
                <li class="list-inline-item"><a href="about_us.php">About Us</a></li>
                <li class="list-inline-item"><a class="category-color" href="contact_us.php">Contact Us</a></li>
            </ul>
        </div>
</body>
</html>
    <form method='POST'>
        <div class="container my-5">
            <div class="row">
                <div class="col-5">
                    <div class="card feedback-card rounded-end-circle bg-light-75">
                        <div class="card-body">
                            <div class="mb-md-5 mt-md-4">
                                <h2 class="fw-bold mb-2 text-uppercase">FEEDBACK</h2>
                                <p class="mb-4">Please give your feedback follow form below !!!</p>

                                <div class="nickname mb-3">
                                    <?php
                                    if (isset($_SESSION['user_nickname'])){
                                        echo '<h4>'. $_SESSION['user_nickname']. '</h4>';
                                    }else {
                                        echo 'You have to <a href="../login/user_login.php">login</a> to leave feedback.';
                                    }
                                    ?>
                                </div>
                                <div class="title mb-3">
                                    <input type="text" placeholder="Enter feedback title here" id="form3Example4" class="" name='fb_title' required />
                                </div>
                                <div class="content mb-3">
                                    <textarea  name="fb_content" class="login text-start custom-textarea" placeholder="Enter feedback content here" id="form3Example3" rows="3" required></textarea>
                                </div>
                                <button class="login-btn" type="submit" name='submit'>Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-2"></div>
                <div class="col-3 feedback-address">
                    <div class="address-lists">
                        <h4 class="fw-bold">CONTACT OFFICE</h4>
                        <p class="fw-light">1 Raffles Place, #40-02 One Raffles Place Office Tower 1 Singapore</p>
                    </div>

                    <div class="address-lists">
                        <h4 class="fw-bold">CANADA OFFICE</h4>
                        <p class="fw-light">Suite 1480 HSBC Building 885 West Georgia Street Vancouver, BC, V6C3E8 Canada</p>
                    </div>

                    <div class="address-lists">
                        <h4 class="fw-bold">LITHUANIA OFFICE</h4>
                        <p class="fw-light">Lvovo str. 25 Mažoji bure 15th floor LT-09320, Vilnius Lithuania</p>
                    </div>
                </div>
                
            </div>

            <div class="row mt-5">
                <div class="col-12">
                    <div class="embed-responsive embed-responsive-16by9" style='width: 100%; height: 90vh'>
                        <iframe class="embed-responsive-item" style='width: 100%; height: 90vh' src="https://www.google.com/maps/d/u/0/embed?mid=1r2nJGA3MVIuLSvgnl4YAO7d92Fy3x6c&ehbc=2E312F&noprof=1"></iframe>
                    </div>
                </div>
            </div>

            
        </div>

    </form>


<?php 
include("../footer.php");
?>