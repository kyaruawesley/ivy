<?php
include 'connect.php';

$errors = array();
$success_message = '';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $age = $_POST['age'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $role = $_POST['role'];

    // Validate first name
    if (empty($first_name)) {
        $errors['first_name'] = 'First name is required';
    }

    // Validate last name
    if (empty($last_name)) {
        $errors['last_name'] = 'Last name is required';
    }

    // Validate age
    if (empty($age)) {
        $errors['age'] = 'Age is required';
    }

    // Validate email
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }

    // Validate password
    if (empty($password)) {
        $errors['password'] = 'Password is required';
    }

    // Validate confirm password
    if (empty($confirm_password)) {
        $errors['confirm_password'] = 'Confirm password is required';
    } elseif ($password != $confirm_password) {
        $errors['confirm_password'] = 'Passwords do not match';
    }


    if (empty($errors)) {
        if ($role == 'buyer') {
            $sql = "INSERT INTO buyers (first_name, last_name, age, email, password) VALUES ('$first_name', '$last_name', $age, '$email', '$password')";
        } else {
            $sql = "INSERT INTO sellers (first_name, last_name, age, email, password) VALUES ('$first_name', '$last_name', $age, '$email', '$password')";
        }

        if (mysqli_query($conn, $sql)) {
            $success_message = 'Sign up successful! You can now login.';
            // Redirect to login page after a delay
            header('location: login.php');
            exit;
        } else {
            $errors[] = 'Error inserting user into database';
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="form.css">
    <title>Sign Up</title>
</head>
<body style="background-image: url('lala.jpg'); background-size: cover; background-repeat: no-repeat;">
    <!-- Sign up page -->
<div class="form-container">
<h1>Sign Up</h1>
<form action="signup.php" method="post">
  <label for="first_name">First Name:</label>
  <input type="text" name="first_name" id="first_name" value="<?php echo isset($_POST['first_name']) ? $_POST['first_name'] : ''; ?>">
  <?php if(isset($errors['first_name'])) { ?>
      <p class="error-message"><?php echo $errors['first_name']; ?></p>
  <?php } ?>
  <br>
  <label for="last_name">Last Name:</label>
  <input type="text" name="last_name" id="last_name" value="<?php echo isset($_POST['last_name']) ? $_POST['last_name'] : ''; ?>">
  <?php if(isset($errors['last_name'])) { ?>
      <p class="error-message"><?php echo $errors['last_name']; ?></p>
  <?php } ?>
  <br>
  <label for="age">Age:</label>
  <input type="number" name="age" id="age" value="<?php echo isset($_POST['age']) ? $_POST['age'] : ''; ?>">
  <?php if(isset($errors['age'])) { ?>
      <p class="error-message"><?php echo $errors['age']; ?></p>
  <?php } ?>
  <br>
  <label for="email">Email:</label>
  <input type="email" name="email" id="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
  <?php if(isset($errors['email'])) { ?>
      <p class="error-message"><?php echo $errors['email']; ?></p>
  <?php } ?>
  <br>
  <label for="password">Password:</label>
  <input type="password" name="password" id="password">
  <?php if(isset($errors['password'])) { ?>
      <p class="error-message"><?php echo $errors['password']; ?></p>
  <?php } ?>
  <br>
  <label for="confirm_password">Confirm Password:</label>
  <input type="password" name="confirm_password" id="confirm_password">
  <?php if(isset($errors['confirm_password'])) { ?>
      <p class="error-message"><?php echo $errors['confirm_password']; ?></p>
  <?php } ?>
  <br>
  <label for="role">Role:</label>
  <select name="role" id="role">
    <option value="buyer">Buyer</option>
    <option value="seller">Seller</option>
  </select>
  <br>
  <input type="submit" value="Sign Up">
</form>
<?php if(!empty($success_message)) { ?>
    <div class="success-message">
        <p><?php echo $success_message; ?></p>
    </div>
<?php } ?>
<p>Already have an account? <a href="login.php">Login here</a></p>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
