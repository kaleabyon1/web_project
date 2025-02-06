<?php
include 'db_connect.php';
session_start();

$data = json_decode(file_get_contents("php://input"), true);//gets json form frontend
if (!isset($data['cart']) || empty($data['cart'])) {
    echo json_encode(["status" => "error", "message" => "Cart is empty"]);
    exit;//checks of its valid data
}
$user_id = $_SESSION['user_id'] ?? 1; // Replace with actual user authentication
$total_price = 0;
foreach ($data['cart'] as $item) {
    $total_price += $item['price'] * $item['quantity'];
}
// puts the order to the database
$stmt = $conn->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
$stmt->bind_param("id", $user_id, $total_price);
$stmt->execute();
$order_id = $stmt->insert_id;
// puts each item to order_items table
$stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
foreach ($data['cart'] as $item) {
    $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
    $stmt->execute();
}
echo json_encode(["status" => "success", "message" => "Order placed successfully"]);
$conn->close();
?>
