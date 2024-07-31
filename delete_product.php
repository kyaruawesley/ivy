<?php
include 'connect.php';
session_start();

// Check if the user is logged in and is a seller
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['user_type'] !== 'seller') {
    header("location: login.php");
    exit;
}

// Only process the deletion if the product ID is provided
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];
    $userId = $_SESSION['user_id'];

    // Delete the product only if it belongs to the seller
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ? AND seller_id = ?");
    $stmt->bind_param("ii", $productId, $userId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Product deleted successfully!";
    } else {
        echo "Failed to delete product or you do not have permission.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Product</title>
</head>
<body>
    <h1>Delete Product</h1>
    <p>Return to your <a href="seller_dashboard.php">dashboard</a>.</p>
</body>
</html>