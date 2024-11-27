<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="https://bootswatch.com/5/darkly/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Your Shopping Cart</h1>

        <?php if (empty($_SESSION['cart'])) : ?>
            <div class="alert alert-warning text-center">
                <p>Your cart is empty. <a href="products_page.php" class="alert-link">Browse products</a> to add items to your cart.</p>
            </div>
        <?php else : ?>
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_price = 0;
                    foreach ($_SESSION['cart'] as $key => $item) :
                        $item_total = $item['price'] * $item['quantity'];
                        $total_price += $item_total;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($item['title']); ?></td>
                            <td><?= number_format($item['price'], 2); ?> €</td>
                            <td><?= htmlspecialchars($item['quantity']); ?></td>
                            <td><?= number_format($item_total, 2); ?> €</td>
                            <td>
                                <a href="remove_from_cart.php?id=<?= $item['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to remove this item?');">
                                    <i class="bi bi-trash"></i> Remove
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <h3>Total: <?= number_format($total_price, 2); ?> €</h3>
                <a href="checkout.php" class="btn btn-success btn-lg">
                    <i class="bi bi-arrow-right-circle"></i> Proceed to Checkout
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
