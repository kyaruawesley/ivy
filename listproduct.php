<?php
session_start(); // Start the session

$errors = array();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to the database (assuming you have already set up the database connection)
    $servername = "localhost"; // Replace with your MySQL server hostname or IP address
    $username = "root";
    $password = "";
    $dbname = "IS";

    try {
        // Create a new PDO instance with error handling
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Get the product details from the form
        $productName = $_POST['product-name'];
        $productDetails = $_POST['product-details'];
        $productPrice = $_POST['product-price'];
        $productType = $_POST['product-type']; // New field for product type

        // Handle image upload
        $imageData = file_get_contents($_FILES['image']['tmp_name']);

        // Get the seller's ID from the session
        $sellerId = $_SESSION['userid'];

        // Fetch seller's first name from the sellers table
        $sql = "SELECT first_name FROM sellers WHERE seller_id = :sellerId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':sellerId', $sellerId);
        $stmt->execute();
        $sellerFirstName = $stmt->fetchColumn();

        // Insert the product details and image into the listings table using prepared statements
        $sql = "INSERT INTO listings (product_name, product_details, price, type, image, sellers_first_name) 
        VALUES (:productName, :productDetails, :productPrice, :productType, :imageData, :sellerFirstName)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':productName', $productName);
        $stmt->bindParam(':productDetails', $productDetails);
        $stmt->bindParam(':productPrice', $productPrice);
        $stmt->bindParam(':productType', $productType);
        $stmt->bindParam(':imageData', $imageData, PDO::PARAM_LOB);
        $stmt->bindParam(':sellerFirstName', $sellerFirstName);
        $stmt->execute();

        // Redirect the user to a success page or display a success message
        $success_message = "Product listed successfully!";

    } catch (PDOException $e) {
        // Handle database errors
        echo "Connection failed: " . $e->getMessage();
    } catch (Exception $e) {
        // Handle other errors
        echo $e->getMessage();
    } finally {
        // Close the database connection
        $conn = null;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Listing</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('popo.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.8);
            max-width: 700px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 24px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 15px;
            color: #555;
        }

        input[type=text],
        input[type=number],
        textarea,
        select,
        input[type=file] {
            width: calc(100% - 22px);
            padding: 15px;
            margin-top: 8px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 18px;
        }

        textarea {
            resize: vertical;
        }

        input[type=submit] {
            margin-top: 25px;
            padding: 15px 25px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 18px;
        }

        input[type=submit]:hover {
            background-color: #0056b3;
        }

        .success-message {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }
        /* Styles for the search link */
.search-link {
    text-align: center;
    margin-bottom: 20px;
}

.search-link a {
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 16px;
}

.search-link a:hover {
    background-color: #0056b3;
}

 .navbar {
            background-color: #007BFF;
            overflow: hidden;
        }

        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
    </style>

    </style>
</head>
<body>
    <div class="navbar">
        <a href="userprofile.php">My Listings</a>
        <a href="searchproduct.php">Product Catalog</a>
    </div>
    <div class="form-container">
        <h1>Product Listing</h1>
        <form action="listproduct.php" method="POST" enctype="multipart/form-data">
            <label for="product-name">Product Name:</label>
            <input type="text" name="product-name" id="product-name" required>

            <label for="product-details">Product Details:</label>
            <textarea name="product-details" id="product-details" required></textarea>

            <label for="product-price">Price:</label>
            <input type="number" name="product-price" id="product-price" step="0.01" required>

            <label for="product-type">Type:</label>
            <select name="product-type" id="product-type" required>
                <option value="for selling">For Selling</option>
                <option value="for auctioning">For Auctioning</option>
            </select>

            <!-- Input field for image upload -->
            <label for="image">Product Image:</label>
            <input type="file" name="image" id="image" required accept="image/*">

            <input type="submit" value="List Product">
            <br>
            
            <?php if (!empty($success_message)) { ?>
                <div class="success-message">
                    <p><?php echo $success_message; ?></p>
                </div>
            <?php } ?>
        </form>
    </div>
</body>
</html>
