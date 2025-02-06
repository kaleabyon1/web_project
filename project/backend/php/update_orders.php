<?php
include 'db_connect.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['order_id']) || !isset($data['status'])) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit;
}

$order_id = $data['order_id'];
$status = $data['status'];

$stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $order_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Order updated successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update order"]);
}

$conn->close();
?>

