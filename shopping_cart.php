<?php
session_start();

// Sample product list (normally from a DB)
$products = [
    1 => ['name' => 'Laptop', 'price' => 50000],
    2 => ['name' => 'Smartphone', 'price' => 25000],
    3 => ['name' => 'Headphones', 'price' => 3000],
];

// Add to cart
if (isset($_POST['add_to_cart'])) {
    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity > 0 && isset($products[$productId])) {
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
    }
}

// Update quantity
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $id => $qty) {
        $_SESSION['cart'][$id] = intval($qty);
    }
}

// Remove item
if (isset($_GET['remove'])) {
    $id = intval($_GET['remove']);
    unset($_SESSION['cart'][$id]);
}

// Clear cart
if (isset($_GET['clear'])) {
    unset($_SESSION['cart']);
}

// Get cart items
$cart = $_SESSION['cart'] ?? [];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart in PHP</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        table { width: 80%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        form { margin: 0; }
        .btn { padding: 5px 10px; cursor: pointer; }
        .add-form { display: inline-block; margin-right: 20px; }
    </style>
</head>
<body>

<h2>🛍️ Product List</h2>

<?php foreach ($products as $id => $product): ?>
    <form method="POST" class="add-form">
        <input type="hidden" name="product_id" value="<?= $id ?>">
        <?= $product['name'] ?> - ₹<?= $product['price'] ?>
        Qty: <input type="number" name="quantity" value="1" min="1" style="width: 50px;">
        <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
    </form>
<?php endforeach; ?>

<hr>

<h2>🛒 Shopping Cart</h2>

<?php if (!empty($cart)): ?>
    <form method="POST">
    <table>
        <tr>
            <th>Item</th>
            <th>Price (₹)</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Action</th>
        </tr>

        <?php $total = 0; ?>
        <?php foreach ($cart as $id => $qty): ?>
            <?php
                $item = $products[$id];
                $subtotal = $item['price'] * $qty;
                $total += $subtotal;
            ?>
            <tr>
                <td><?= $item['name'] ?></td>
                <td><?= $item['price'] ?></td>
                <td>
                    <input type="number" name="quantities[<?= $id ?>]" value="<?= $qty ?>" min="1" style="width: 60px;">
                </td>
                <td><?= $subtotal ?></td>
                <td><a href="?remove=<?= $id ?>" onclick="return confirm('Remove item?')">Remove</a></td>
            </tr>
        <?php endforeach; ?>

        <tr>
            <th colspan="3">Total</th>
            <th colspan="2">₹<?= $total ?></th>
        </tr>
    </table>

    <button type="submit" name="update_cart" class="btn">Update Cart</button>
    <a href="?clear=1" onclick="return confirm('Clear cart?')" class="btn">Clear Cart</a>
    </form>
<?php else: ?>
    <p>Your cart is empty.</p>
<?php endif; ?>

</body>
</html>
