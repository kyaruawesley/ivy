<?php 
session_start(); // Start the session

// Only proceed if the user is logged in
if (!isset($_SESSION['userid'])) {
    // Redirect to login page or display an error
    header('Location: login.php');
    exit();
}

// Check if deleteid is provided in the query string
if (!isset($_GET['deleteid'])) {
    // Redirect or display an error message
    header('Location: userprofile.php');
    exit();
}

$errors = array();
$listingId = $_GET['deleteid'];

// Connect to the database (assuming you have already set up the database connection)
$servername = "localhost"; // Replace with your MySQL server hostname or IP address
$username = "root";
$password = "";
$dbname = "IS";

try {
    // Create a new PDO instance with error handling
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Delete the listing from the database
    $sql = "DELETE FROM listings WHERE listing_id = :listingId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':listingId', $listingId);
    $stmt->execute();

    // Redirect to the display page after deletion
    header("Location: userprofile.php");
    exit();

} catch (PDOException $e) {
    $errors[] = "Connection failed: " . $e->getMessage();
}

// Close the database connection
$conn = null;
?>
