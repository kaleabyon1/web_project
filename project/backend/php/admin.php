<?php
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../../frontend/css/style.css">
    <script src="../../frontend/js/admin_dashboard.js" defer></script>
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
        <h1>Admin Dashboard</h1>
        <p>Welcome to the admin panel. Select an option above to manage the platform.</p>

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
        <h2>Orders</h2>
            <div id="orders-container"></div>
</section>
    </main>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        console.log("Admin page loaded, calling loadOrders...");
        loadOrders();
    });
</script>
</body>
</html>