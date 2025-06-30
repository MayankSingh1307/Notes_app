<?php
$products = [
    ['name' => 'Smartphone', 'category' => 'Electronics'],
    ['name' => 'Laptop', 'category' => 'Electronics'],
    ['name' => 'T-shirt', 'category' => 'Clothing'],
    ['name' => 'Jeans', 'category' => 'Clothing'],
    ['name' => 'Novel', 'category' => 'Books'],
];

// Get selected category from form (default to 'All')
$selectedCategory = $_POST['category'] ?? 'All';

// Filter products using array_filter (similar to JS filter())
$filteredProducts = ($selectedCategory === 'All')
    ? $products
    : array_filter($products, function ($product) use ($selectedCategory) {
        return $product['category'] === $selectedCategory;
    });
?>

<!DOCTYPE html>
<html>
<head>
    <title>Filter Products by Category (PHP)</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        select { padding: 5px 10px; }
        .product { padding: 10px; border-bottom: 1px solid #ccc; }
    </style>
</head>
<body>

<h2>🛍️ Product List</h2>

<form method="POST">
    <label for="category">Filter by category:</label>
    <select name="category" onchange="this.form.submit()">
        <option value="All" <?= $selectedCategory == 'All' ? 'selected' : '' ?>>All</option>
        <option value="Electronics" <?= $selectedCategory == 'Electronics' ? 'selected' : '' ?>>Electronics</option>
        <option value="Clothing" <?= $selectedCategory == 'Clothing' ? 'selected' : '' ?>>Clothing</option>
        <option value="Books" <?= $selectedCategory == 'Books' ? 'selected' : '' ?>>Books</option>
    </select>
</form>

<hr>

<?php if (count($filteredProducts) > 0): ?>
    <?php foreach ($filteredProducts as $product): ?>
        <div class="product">
            <?= htmlspecialchars($product['name']) ?> (<?= $product['category'] ?>)
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No products found in this category.</p>
<?php endif; ?>

</body>
</html>
