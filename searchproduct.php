<?php
// Start the session
session_start();

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "IS";

// Create a new PDO instance with error handling
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

// Check for a search query
$searchQuery = '';
if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET['search'])) {
    $searchQuery = $_GET['search'];
}

// Retrieve product listings from the database
try {
    // If there's a search query, filter the results
    if (!empty($searchQuery)) {
        $stmt = $conn->prepare("SELECT * FROM listings WHERE product_name LIKE :searchQuery");
        $stmt->bindValue(':searchQuery', '%'.$searchQuery.'%');
    } else {
        $stmt = $conn->query("SELECT * FROM listings");
    }
    $stmt->execute();
    $listings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Products</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Global Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f5f7;
            color: #333;
            line-height: 1.6;
            background-image: url(phase4.jpg);
            background-size: cover;
            background-repeat: no-repeat;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 40px;
            color: #333;
            font-weight: 500;
            font-size: 2.5rem;
        }

        .product-listings {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .product {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }

        .product img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-details {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .product-details h2 {
            font-size: 1.25rem;
            margin-bottom: 10px;
            color: #333;
            font-weight: 500;
        }

        .product-details p {
            flex-grow: 1;
            color: #666;
        }

        .product-details p.price {
            color: #e55347;
            font-weight: 700;
            font-size: 1.5rem;
            margin: 10px 0;
        }

        .product-details .type {
            color: #333;
            font-size: 0.9rem;
            background: #e2e2e2;
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            margin-top: auto; /* Aligns 'Type' to the bottom */
        }

        /* Responsive adjustments */
        @media (min-width: 768px) {
            .product img {
                height: 250px;
            }
        }
        .navbar {
            background-color: #007bff;
            padding: 10px 20px;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
        }

        .navbar a:hover {
            background-color: #0056b3;
            border-radius: 4px;
        }

        .profile-panel {
            display: flex;
            align-items: center;
        }

        .profile-panel img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .search-bar {
            margin: 20px 0;
            display: flex;
            justify-content: center;
        }

        .search-bar input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-right: 10px;
            border: none;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .search-bar button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #0056b3;
        }
        .action-button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.action-button:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-links">
            <a href="index.html">Home</a>
            <a href="about.html">About Us</a>
            <a href="logout.php">Log out</a>
        </div>
        <div class="profile-panel">
            <a href="profile.php">Profile</a>
        </div>
    </nav>

    <div class="container">
        <h1>Explore Our Product Catalog</h1>

        <!-• Search Bar -->
        <div class="search-bar">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                <input type="text" name="search" id="searchInput" placeholder="Search for products..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button type="submit" id="searchButton">Search</button>
            </form>
        </div>

        <!-• Display product listings -->
       <div class="product-listings">
    <?php if (empty($listings)): ?>
        <p>No products found.</p>
    <?php else: ?>
        <?php foreach ($listings as $listing) : ?>
            <div class="product">
                <?php if (!empty($listing['image'])) : ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($listing['image']); ?>" alt="<?php echo htmlspecialchars($listing['product_name']); ?>" />
                <?php else : ?>
                    <img src="https://via.placeholder.com/300" alt="No Image Available" />
                <?php endif; ?>
                <div class="product-details">
                    <h2><?php echo htmlspecialchars($listing['product_name']); ?></h2>
                    <p><?php echo htmlspecialchars($listing['product_details']); ?></p>
                    <p class="price">Price: <?php echo htmlspecialchars($listing['price']); ?>Ksh</p>
                    <p class="type">Type: <?php echo htmlspecialchars($listing['type']); ?></p>
                    <p class="seller">Seller: <?php echo htmlspecialchars($listing['sellers_first_name']); ?></p>
                    <?php if ($listing['type'] === 'for auctioning') : ?>
                        <a href="placebid.php?listing_id=<?php echo $listing['listing_id']; ?>" class="action-button">Bid Now!</a>
                    <?php elseif ($listing['type'] === 'for selling') : ?>
                        <a href="checkout.php?listing_id=<?php echo $listing['listing_id']; ?>" class="action-button">Buy Now!</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

    <script>
        // If you have any JavaScript, you can place it here.
    </script>
</body>
</html>