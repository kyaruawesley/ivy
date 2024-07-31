<?php
session_start(); // Start the session

include 'connect.php';

$errors = array();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    }

    // If there are no errors, attempt to log in
    if (empty($errors)) {
        $sql = "SELECT * FROM buyers WHERE email = '$email' AND password = '$password'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['email'] = $email; // Set email in session
            $_SESSION['userid'] = $row['buyer_id']; // Set userid in session
            $_SESSION['user_role'] = 'buyer'; // Set user role
            header('Location: index.html'); // Redirect buyer to buyer profile
            exit;
        } else {
            $sql = "SELECT * FROM sellers WHERE email = '$email' AND password = '$password'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                $_SESSION['email'] = $email; // Set email in session
                $_SESSION['userid'] = $row['seller_id']; // Set userid in session
                $_SESSION['user_role'] = 'seller'; // Set user role
                header('Location: userprofile.php'); // Redirect seller to seller profile
                exit;
            } else {
                $errors[] = 'Invalid email or password';
            }
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="form.css">
   <style>
        .error-message {
            color: red;
            /* Add additional styling as needed */
            font-weight: bold; /* Makes the text bold */
            margin-bottom: 20px; /* Adds space below the error message div */
        }
    </style>
</head>
<body style="background-image: url('lala.jpg'); background-size: cover; background-repeat: no-repeat;">
    <!-- Login page -->
<?php include 'connect.php'; ?>
<div class="form-container">
<h1>Login</h1>
<form action="login.php" method="post">
  <label for="email">Email:</label>
  <input type="email" name="email" id="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
  <br>
  <label for="password">Password:</label>
  <input type="password" name="password" id="password">
  <br>
  <input type="submit" value="Login">
  <?php if (!empty($errors)) { ?>
        <div class="error-message">
            <?php foreach ($errors as $error) {
                echo '<p>' . htmlspecialchars($error) . '</p>';
            } ?>
        </div>
    <?php } ?>
</form>

<p>Don't have an account? <a href="signup.php">Sign up here</a></p>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
