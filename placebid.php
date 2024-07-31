<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Bid</title>
    <style type="text/css">
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

header {
    background-color: #007bff;
    color: white;
    padding: 1rem;
    text-align: center;
}

main {
    padding: 2rem;
}

form {
    display: flex;
    flex-direction: column;
    align-items: center;
}

label {
    margin-bottom: 0.5rem;
}

input {
    padding: 0.5rem;
    margin-bottom: 1rem;
    width: 100%;
}

button {
    padding: 0.5rem 1rem;
    background-color: #333;
    color: white;
    border: none;
    cursor: pointer;
}

button:hover {
    background-color: #555;
}

#bidStatus {
    margin-top: 1rem;
    text-align: center;
}
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

    </style>
</head>
<body>
    <header>
        <h1>Place Your Bid</h1>
    </header>
    <main>
        <form id="placeBidForm">
            <label for="bidAmount">Bid Amount:</label>
            <input type="number" id="bidAmount" name="bidAmount" required>
            <button type="submit">Place Bid</button>
        </form>
        <div id="bidStatus"></div>
    </main>
      <div class="search-link">
        <a href="searchproduct.php">Go back to our products catalog to explore other products</a>
    </div>
    <script>
        const placeBidForm = document.getElementById('placeBidForm');
const bidStatus = document.getElementById('bidStatus');

placeBidForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const bidAmount = parseFloat(document.getElementById('bidAmount').value);

    if (!bidAmount || bidAmount <= 0) {
        bidStatus.textContent = 'Please enter a valid bid amount.';
        return;
    }

    // Here, you can implement the logic to handle the bid submission, such as sending a request to your server.
    // For demonstration purposes, we'll simply display a success message.
    bidStatus.textContent = `Bid placed successfully. Amount: Ksh ${bidAmount} we'll notify you on the status of your bid`;
    placeBidForm.reset();
});
    </script>
</body>
</html>