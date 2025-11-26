<?php
$conn = new mysqli("localhost", "root", "", "restaurant_db");

if (!isset($_GET["order_id"])) {
    die("Invalid receipt.");
}

$order_id = intval($_GET["order_id"]);


$order = $conn->query("SELECT * FROM orders_tbl WHERE order_id = $order_id")->fetch_assoc();


$items = json_decode($order["order_items"], true);
?>
<!DOCTYPE html>
<html>
<head>
<title>Receipt</title>
<style>
    body {
        font-family: Arial;
        background: #1a1a1a;
        color: white;
        padding: 40px;
    }
    .card {
        background: #222;
        padding: 25px;
        max-width: 650px;
        margin: auto;
        border-radius: 10px;
        border: 1px solid #ff9900;
    }
    h2 {
        color: #ff9900;
        text-align: center;
    }
    table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }
    td {
        padding: 8px 0;
        vertical-align: middle;
    }
    .item-img {
        width: 55px;
        height: 55px;
        object-fit: cover;
        border-radius: 5px;
        margin-right: 10px;
        border: 1px solid #ff9900;
    }
    .total {
        font-weight: bold;
        margin-top: 20px;
        text-align: right;
        color: #ffcc66;
        font-size: 18px;
    }
</style>
</head>
<body>
<div class="card">
    <h2>Receipt</h2>

    <p><b>Order ID:</b> <?= $order_id ?></p>
    <p><b>Order Type:</b> <?= $order["order_type"] ?></p>

    <?php if ($order["order_type"] == "Delivery"): ?>
        <p><b>Customer Name:</b> <?= htmlspecialchars($order["customer_name"]) ?></p>
        <p><b>Address:</b> 
            <?= !empty($order["customer_address"]) ? htmlspecialchars($order["customer_address"]) : "N/A" ?>
        </p>
    <?php endif; ?>

    <hr style="border-color:#444">

    <table>
        <?php 
        foreach ($items as $item): 
            
            $name = $conn->real_escape_string($item["name"]);
            $imgQuery = $conn->query("SELECT image FROM menu_tbl WHERE Dish_Info = '$name'");
            $imgRow = $imgQuery->fetch_assoc();
            $image = $imgRow ? $imgRow["image"] : "no-image.png";
        ?>
        <tr>
            <td style="display:flex; align-items:center;">
                <img src="uploads/<?= $image ?>" class="item-img">
                <?= $item["name"] ?> (x<?= $item["qty"] ?>)
            </td>
            <td style="text-align:right;">
                ₱<?= number_format($item["qty"] * $item["price"], 2) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <p class="total">Total: ₱<?= number_format($order["total_price"], 2) ?></p>
</div>
</body>
</html>
