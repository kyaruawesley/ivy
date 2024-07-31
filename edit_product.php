<?php
include 'connect.php';
session_start();

// Check if the user is logged in and is a seller
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['user_type'] !== 'seller') {
    header("location: login.php");
    exit;
}

// Fetch the product details only if the ID belongs to the seller
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];
    $userId = $_SESSION['user_id'];

    // Fetch the product details
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
    $stmt->bind_param("ii", $productId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo "Product not found or you do not have permission to edit it.";
        exit;
    }
}

// Handle the form submission and update the product details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_changes'])) {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $productDetails = $_POST['product_details'];
    $price = $_POST['price'];

    // Update the product details
    $updateStmt = $conn->prepare("UPDATE products SET product_name = ?, product_details = ?, price = ? WHERE id = ? AND seller_id = ?");
    $updateStmt->bind_param("ssdii", $productName, $productDetails, $price, $productId, $userId);
    $updateStmt->execute();

    if ($updateStmt->affected_rows > 0) {
        echo "Product updated successfully!";
    } else {
        echo "Failed to update product or no changes were made.";
    }
    
    $updateStmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
</head>
<body>
    <h1>Edit Product</h1>
    <?php if (isset($product)): ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
        <label for="product_name">Product Name:</label><br>
        <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required><br><br>
        
        <label for="product_details">Product Details:</label><br>
        <textarea id="product_details" name="product_details" rows="4" required><?php echo htmlspecialchars($product['product_details']); ?></textarea><br><br>
        
        <label for="price">Price:</label><br>
        <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required><br><br>
        
        <input type="submit" name="save_changes" value="Save Changes">
    </form>
    <?php endif; ?>
</body>
</html>