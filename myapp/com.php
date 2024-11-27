<?php
require 'db.php';

$sql = "SELECT * FROM products";
$stmt = $pdo->query($sql);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($products as $product) {
    echo "<div>";
    echo "<h3>" . htmlspecialchars($product['title']) . "</h3>";
    echo "<p>Price: $" . htmlspecialchars($product['price']) . "</p>";
    echo "<button onclick='addToCart(" . $product['id'] . ")'>Add to Cart</button>";
    echo "</div>";
}
?>
<script>
function addToCart(productId) {
    fetch(`/myapp/addToCart.php?id=${productId}`)
        .then(response => response.text())
        .then(data => alert(data));
}
</script>
