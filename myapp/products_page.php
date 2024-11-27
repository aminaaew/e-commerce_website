<?php
session_start();

// Gestion de la déconnexion
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: login.php");
    exit;
}

// Load products from the JSON file
$json_file = 'products.json';
if (file_exists($json_file)) {
    $json_content = file_get_contents($json_file);
    $products = json_decode($json_content, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Error decoding JSON: " . json_last_error_msg());
    }
} else {
    die("Error: JSON file not found.");
}

// Check if the form has been submitted to add to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Find the product in the JSON data by its ID
    foreach ($products as $product) {
        if ($product['id'] == $product_id) {
            $cart_item = [
                'id' => $product['id'],
                'title' => $product['title'],
                'price' => $product['price'],
                'quantity' => 1 // default to 1 quantity
            ];

            // Add the product to the cart
            $_SESSION['cart'][] = $cart_item;

            // Redirect to the cart page to show the cart
            header("Location: cart.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://bootswatch.com/5/darkly/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="products_page.php">E-Commerce</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white" href="?action=logout">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        <h1 class="text-center mb-4">Available Products</h1>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <!-- Display product image -->
                        <img 
                            src="<?= htmlspecialchars($product['thumbnail'] ?? 'default-placeholder.png'); ?>" 
                            class="card-img-top" 
                            alt="<?= htmlspecialchars($product['title'] ?? 'Product Image'); ?>" 
                            style="height: 200px; object-fit: cover;"
                        >
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['title'] ?? 'Name unavailable'); ?></h5>
                            <p class="card-text"><?= htmlspecialchars($product['description'] ?? 'Description unavailable'); ?></p>
                            <p class="card-text"><strong>Price:</strong> <?= htmlspecialchars($product['price'] ?? '0.00'); ?> €</p>
                            <p class="card-text"><strong>Rating:</strong> <?= htmlspecialchars($product['rating'] ?? 'No rating'); ?></p>
                            <p class="card-text"><strong>Stock:</strong> <?= htmlspecialchars($product['stock'] ?? '0'); ?> items available</p>
                            <p class="card-text"><strong>Category:</strong> <?= htmlspecialchars($product['category'] ?? 'Uncategorized'); ?></p>

                            <!-- Add to Cart Form -->
                            <form action="products_page.php" method="POST">
                                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']); ?>">
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
