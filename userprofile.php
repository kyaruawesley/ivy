<?php
session_start(); // Start the session

// Only proceed if the user is logged in
if (!isset($_SESSION['userid'])) {
    // Redirect to login page or display an error
    header('Location: login.php');
    exit();
}

$errors = array();
$listings = array();

// Connect to the database (assuming you have already set up the database connection)
$servername = "localhost"; // Replace with your MySQL server hostname or IP address
$username = "root";
$password = "";
$dbname = "IS";

try {
    // Create a new PDO instance with error handling
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the seller's first name from the session using their user ID
    $sellerId = $_SESSION['userid'];
    $sql = "SELECT first_name FROM sellers WHERE seller_id = :sellerId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':sellerId', $sellerId);
    $stmt->execute();
    $sellerFirstName = $stmt->fetchColumn();

    if ($sellerFirstName) {
        // Fetch all listings from the database for the current seller by their first name
        $sql = "SELECT * FROM listings WHERE sellers_first_name = :sellerFirstName";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':sellerFirstName', $sellerFirstName);
        $stmt->execute();
        $listings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $errors[] = "Seller not found.";
    }

} catch (PDOException $e) {
    $errors[] = "Connection failed: " . $e->getMessage();
}

// Close the database connection
$conn = null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Listings</title>
    <style>
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
            max-width: 1200px;
            margin: 0 auto;
            border: 1px solid #ddd; /* Add border around the whole page */
            border-radius: 8px;
            background-color: #fff; /* White background */
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #0056b3; /* Blue color for headings */
            text-align: center;
            margin-bottom: 1em;
        }

        p {
            color: #555; /* Dark gray color for paragraphs */
            line-height: 1.5;
            margin-bottom: 1em;
        }

        .error-messages p {
            color: red; /* Red color for error messages */
            text-align: center;
        }

        .listings-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
        }

        .listing {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 20px;
            width: calc(33.333% - 20px); /* Fix typo and adjust width */
        }

        .listing h2 {
            background-color: #007bff; /* Blue background for product name */
            color: white;
            margin: 0;
            padding: 15px;
        }

        .listing p {
            padding: 15px;
            margin: 0;
            color: #333;
        }

        .listing img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        .add-listing-button {
            display: block;
            width: 200px;
            margin: 0 auto 20px; /* Center the button */
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-listing-button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        @media (max-width: 768px) {
            .listings-container {
                justify-content: center;
            }

            .listing {
                width: calc(50% - 20px); /* Adjust width for smaller screens */
            }
        }

        @media (max-width: 480px) {
            .listing {
                width: 100%; /* Full width on smaller screens */
            }
        }
         .button {
            display: inline-block;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button.delete-button {
            background-color: #dc3545; /* Red color for delete button */
            color: #fff;
        }

        .button.update-button {
            background-color: #28a745; /* Green color for update button */
            color: #fff;
        }

        .button:hover {
            filter: brightness(85%);
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Welcome <?php echo $_SESSION['email']; ?> to your Listings</h1>
    <p>Here you can find all the products you've listed. Click the button below to add a new listing.</p>
    <a href="listproduct.php" class="add-listing-button">Add New Listing</a>

    <?php if (count($errors) > 0): ?>
        <div class="error-messages">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (count($listings) > 0): ?>
        <div class="listings-container">
            <?php foreach ($listings as $listing): ?>
                <div class="listing">
                    <h2><?php echo htmlspecialchars($listing['product_name']); ?></h2>
                    <p><?php echo nl2br(htmlspecialchars($listing['product_details'])); ?></p>
                    <p>Price: <?php echo htmlspecialchars($listing['price']); ?>Ksh</p>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($listing['image']); ?>" alt="Product Image">
                     <!-- Delete and Update buttons -->
                    <div class="button-container">
                        <a href="delete.php?deleteid=<?php echo $listing['listing_id']; ?>" class="button delete-button" onclick="return confirm('Are you sure you want to delete this listing?');">Delete</a>
                        <a href="update.php?updateid=<?php echo $listing['listing_id']; ?>" class="button update-button">Update</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No listings found.</p>
    <?php endif; ?>
</div>
</body>
</html>

