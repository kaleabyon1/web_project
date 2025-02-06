<?php
include 'db_connect.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['name'], $data['address'], $data['payment'], $data['cart'])) {
    echo json_encode(["status" => "error", "message" => "Invalid order data"]);
    exit;
}

$name = $conn->real_escape_string($data['name']);
$address = $conn->real_escape_string($data['address']);
$payment = $conn->real_escape_string($data['payment']);
$cart = $data['cart'];

$sql = "INSERT INTO orders (name, address, payment_method) VALUES ('$name', '$address', '$payment')";
if ($conn->query($sql) === TRUE) {
    $orderId = $conn->insert_id;

    foreach ($cart as $product) {
        $productId = (int)$product['id'];
        $quantity = (int)$product['quantity'];
        $conn->query("INSERT INTO order_items (order_id, product_id, quantity) VALUES ($orderId, $productId, $quantity)");
    }

    echo json_encode(["status" => "success", "message" => "Order placed successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
}

$conn->close();
?>
