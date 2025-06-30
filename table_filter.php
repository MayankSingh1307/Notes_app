<?php
// Sample product list (normally from database)
$products = [
    ['name' => 'Laptop', 'category' => 'Electronics'],
    ['name' => 'Smartphone', 'category' => 'Electronics'],
    ['name' => 'T-shirt', 'category' => 'Clothing'],
    ['name' => 'Jeans', 'category' => 'Clothing'],
    ['name' => 'Book', 'category' => 'Books']
];

// Get search query from form
$search = strtolower($_GET['search'] ?? '');

// Filter the products
$filtered = array_filter($products, function ($product) use ($search) {
    $name = strtolower($product['name']);
    $category = strtolower($product['category']);
    return str_contains($name, $search) || str_contains($category, $search);
});
?>

<!DOCTYPE html>
<html>
<head>
    <title>Table Filter in PHP</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        input[type="text"] { padding: 8px; width: 300px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ccc; }
    </style>
</head>
<body>

<h2>🔍 Filter Products (PHP)</h2>

<form method="GET">
    <input type="text" name="search" placeholder="Search name or category" value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
</form>

<?php if (!empty($filtered)): ?>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filtered as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['category']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No products match your search.</p>
<?php endif; ?>

</body>
</html>
