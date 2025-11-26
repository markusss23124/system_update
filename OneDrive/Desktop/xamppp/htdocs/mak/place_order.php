<?php
header("Content-Type: application/json");


$conn = new mysqli("localhost", "root", "", "restaurant_db");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB connection failed"]);
    exit;
}


$data = json_decode(file_get_contents("php://input"), true);

$order_type = $data["order_type"];
$total_price = $data["total_price"];
$customer_name = $data["customer_name"] ?? "";
$customer_address = $data["delivery_address"] ?? "";
$items = json_encode($data["items"]); 


$sql = "INSERT INTO orders_tbl (order_type, order_items, total_price, customer_name, customer_address)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssdss", $order_type, $items, $total_price, $customer_name, $customer_address);
$stmt->execute();

$order_id = $stmt->insert_id;


echo json_encode([
    "success" => true,
    "order_id" => $order_id
]);
?>
