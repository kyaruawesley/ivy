
<?php
include 'connect.php';

function userExists($username) {
    global $conn;
    // Check if the username exists in buyers and sellers tables
    // ... Implementation
}

function registerUser($userType, $userData) {
    global $conn;
    // Insert the user data into buyers or sellers table based on $userType
    // ... Implementation
}

// Other shared functions...
?>
