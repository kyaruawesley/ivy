
<?php
session_start(); // Start the session

// Only proceed if the user is logged in
if (!isset($_SESSION['userid'])) {
    // Redirect to login page or display an error
    header('Location: login.php');
    exit();
}

$errors = array();

// Check if the update form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $listingId = $_POST['listing_id'];
    $productName = htmlspecialchars($_POST['product_name']);
    $productDetails = htmlspecialchars($_POST['product_details']);
    $price = $_POST['price'];

    // Perform update operation in the database
    try {
        // Connect to the database (assuming you have already set up the database connection)
        $servername = "localhost"; // Replace with your MySQL server hostname or IP address
        $username = "root";
        $password = "";
        $dbname = "IS";

        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare update statement
        $sql = "UPDATE listings SET product_name = :productName, product_details = :productDetails, price = :price WHERE listing_id = :listingId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':productName', $productName);
        $stmt->bindParam(':productDetails', $productDetails);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':listingId', $listingId);
        $stmt->execute();

        // Redirect back to listings page after successful update
        header('Location: userprofile.php');
        exit();
    } catch (PDOException $e) {
        $errors[] = "Update failed: " . $e->getMessage();
    }
}

// If update form is not submitted or update operation failed, display the update form
if (!isset($_POST['submit']) || count($errors) > 0) {
    // Fetch listing details from the database based on listing ID
    $listingId = $_GET['updateid'];

    try {
        // Connect to the database (assuming you have already set up the database connection)
        $servername = "localhost"; // Replace with your MySQL server hostname or IP address
        $username = "root";
        $password = "";
        $dbname = "IS";

        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch listing details for the given listing ID
        $sql = "SELECT * FROM listings WHERE listing_id = :listingId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':listingId', $listingId);
        $stmt->execute();
        $listing = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $errors[] = "Connection failed: " . $e->getMessage();
    }

    // Close the database connection
    $conn = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Listing</title>
    <style type="text/css">
   body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0; /* Light background */
            margin: 0;
            padding: 20px;
            background-image: url(lala.jpg);
            background-size: cover;
            background-repeat: no-repeat;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff; /* White background */
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #0056b3; /* Blue color for headings */
            text-align: center;
            margin-bottom: 1em;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        textarea,
        input[type="number"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        .error-messages {
            color: red; /* Red color for error messages */
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Update Listing</h1>
    <?php if (count($errors) > 0): ?>
        <div class="error-messages">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="listing_id" value="<?php echo $listing['listing_id']; ?>">
            <label for="product_name">Product Name:</label><br>
            <input type="text" id="product_name" name="product_name" value="<?php echo $listing['product_name']; ?>"><br>
            <label for="product_details">Product Details:</label><br>
            <textarea id="product_details" name="product_details"><?php echo $listing['product_details']; ?></textarea><br>
            <label for="price">Price:</label><br>
            <input type="text" id="price" name="price" value="<?php echo $listing['price']; ?>"><br><br>
            <input type="submit" name="submit" value="Update Listing">
        </form>
    <?php endif; ?>
</div>
</body>
</html>

