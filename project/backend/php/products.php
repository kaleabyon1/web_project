<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? null;
    $price = $_POST['price'] ?? null;
    $image = $_FILES['image']['name'] ?? null;
    if (!$name || !$price || !$image) {
        echo json_encode(["status" => "error", "message" => "Missing required fields"]);
        exit;
    }
    $target_dir = __DIR__ . "/uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Create directory if it doesn't exist
    }
    $target_file = $target_dir . basename($image);
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $sql = "INSERT INTO products (name, price, image) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sds", $name, $price, $image);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Product added successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database error: " . $stmt->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to upload image"]);
    }
}
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="../../frontend/css/products.css">
</head>
<body>
    <header>
        <nav>
            <a href="../../frontend/index.html">Home</a>
            <a href="products.php">Manage Products</a>
            <a href="users.php">Manage Users</a>
        </nav>
    </header>
    
    <main>
        <h1>Manage Products</h1>
        <p>Manage and add products to your store.</p>
        <section>
            <h2>Add Product</h2>
            <form action="products.php" method="POST" enctype="multipart/form-data">
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="price">Price:</label>
                <input type="number" id="price" name="price" step="0.01" required>

                <label for="image">Product Image:</label>
                <input type="file" id="image" name="image" accept="image/*" required>

                <button type="submit">Add Product</button>
            </form>
        </section>

        <section>
            <h2>Product List</h2>
            <div class="product-list">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($product = $result->fetch_assoc()): ?>
                        <div class="product-item">
                            <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <p>Name: <?php echo htmlspecialchars($product['name']); ?></p>
                            <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No products found.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>
</html>



