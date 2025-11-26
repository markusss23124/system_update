  <?php
session_start();
include("connection_db.php");


if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== "admin") {
    header("Location: /mak/login-admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>List of Dishes</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
 
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: "Poppins", Arial, sans-serif;
    background-image: url("uploads/v602-nunoon-40-rippednotes.jpg");
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    background-attachment: fixed;
    padding-bottom: 50px;
}


.container-my5 {
    max-width: 1100px;
    margin: 60px auto;
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(8px);
    padding: 35px;
    border-radius: 18px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}


h2, h3, h4 {
    text-align: center;
    font-weight: 700;
    color: #4a2e00;
    margin-bottom: 20px;
    letter-spacing: .5px;
}


.btn-add {
    background: #ffb749;
    color: #442c00;
    font-weight: 600;
    padding: 10px 18px;
    border-radius: 10px;
    border: none;
    transition: .25s;
}

.btn-add:hover {
    background: #ff9f0f;
    color: black;
}


table {
    width: 100%;
    border-radius: 15px;
    overflow: hidden;
    background: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

thead th {
    background: #ffe7c2;
    color: #3d2600;
    padding: 14px;
    font-weight: 600;
}

tbody td {
    padding: 14px;
    color: #4a2e00;
    border-bottom: 1px solid #f2d7b0;
}


.btn-primary {
    background: #ffb749;
    border: none;
    color: #3d2600;
    font-weight: 600;
}

.btn-primary:hover {
    background: #ff9f0f;
    color: black;
}

.btn-danger {
    background: #ff6b5c;
    border: none;
    font-weight: 600;
}

.btn-danger:hover {
    background: #d94a3b;
}


.logout-btn {
    position: absolute;
    top: 20px;
    right: 20px;
    border: 2px solid #ffb749;
    color: #6b4b15;
    padding: 8px 18px;
    background: rgba(255,255,255,0.6);
    backdrop-filter: blur(4px);
    font-weight: 600;
    border-radius: 10px;
    transition: .25s;
}

.logout-btn:hover {
    background: #ffb749;
    color: black;
}


.summary-box {
    background: #fff5e5;
    border: 2px solid #ffcc8a;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 25px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}

.summary-box p {
    font-size: 18px;
    color: #3d2600;
}


.filter-container {
    background: rgba(255,244,230,0.9);
    padding: 20px;
    border-radius: 15px;
    border: 2px solid #ffcf9e;
    margin-bottom: 35px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.filter-container label {
    font-weight: 600;
    color: #5c3b00;
}

.filter-container button {
    background: #ff9f0f;
    border: none;
    padding: 8px 14px;
    border-radius: 10px;
    color: white;
    margin-top: 25px;
    font-weight: 600;
}

.filter-container button:hover {
    background: #ea7f00;
}

</style>

</head>
<body>

<a href="/mak/logout.php" class="logout-btn">Log-out</a>

<div class="container-my5">
    <h2>List of Dishes</h2>
    <a class="btn btn-add" href="/mak/create.php">+ Add New Dish</a>

    <table class="table">
        <thead>
            <tr>
                <th>Dish ID</th>
                <th>Image</th>
                <th>Dish Name</th>
                <th>Price</th>
                <th>Dish Type</th>
                <th>Edit/Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $sql = "SELECT * FROM menu_tbl";
                $result = $conn->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $imagePath = $row['image'] ? "uploads/" . $row['image'] : "uploads/noimage.png";

                    echo "<tr>
                        <td>{$row['dish_id']}</td>
                        <td><img src='$imagePath' width='60' height='60' style='object-fit:cover; border-radius:5px;'></td>
                        <td>{$row['Dish_Info']}</td>
                        <td>₱{$row['Price']}</td>
                        <td>{$row['Food_Type']}</td>
                        <td>
                            <a class='btn btn-primary btn-sm' href='/mak/edit.php?id={$row['dish_id']}'>Edit</a>
                            <a class='btn btn-danger btn-sm' href='/mak/delete.php?id={$row['dish_id']}'>Delete</a>
                        </td>
                    </tr>";
                }
            ?>
        </tbody>
    </table>

    
    <div style="background:#111;padding:20px;border-radius:10px;border:1px solid #ff9900;color:white;margin-bottom:30px;">
        <h4 style="color:#ff9900;">Filter Sales by Date</h4>

        <form method="GET" style="display:flex; gap:20px; flex-wrap:wrap;">
            <div>
                <label>From:</label>
                <input type="date" name="from" class="form-control" required>
            </div>

            <div>
                <label>To:</label>
                <input type="date" name="to" class="form-control" required>
            </div>

            <button style="margin-top:24px;background:#ff9900;color:white;border:none;padding:8px 15px;border-radius:5px;">Apply</button>
        </form>
    </div>

<?php
$where = "";
if (!empty($_GET['from']) && !empty($_GET['to'])) {
    $from = $_GET['from'];
    $to   = $_GET['to'];
    $where = "WHERE DATE(order_date) BETWEEN '$from' AND '$to'";
}
?>

<hr style="border-color:#ff9900; margin:40px 0;">

<h2>Sales & Orders Summary</h2>

<?php

$salesQuery = $conn->query("
    SELECT SUM(total_price) AS total_sales 
    FROM orders_tbl 
    $where
");
$totalSales = $salesQuery->fetch_assoc()['total_sales'] ?? 0;


$orderCountQuery = $conn->query("
    SELECT COUNT(*) AS total_orders 
    FROM orders_tbl 
    $where
");
$totalOrders = $orderCountQuery->fetch_assoc()['total_orders'] ?? 0;
?>

<div style="background:#111;padding:20px;border-radius:10px;border:1px solid #ff9900;margin-bottom:30px;color:white;">
    <h4 style="color:#ff9900;">Overall Sales</h4>

    <p style="font-size:18px;">
        Total Income: 
        <b style="color:#ffcc66;">₱<?= number_format($totalSales, 2) ?></b>
    </p>

    <p style="font-size:18px;">
        Total Orders:
        <b style="color:#ffcc66;"><?= $totalOrders ?></b>
    </p>
</div>



<h3 style="color:#ff9900; margin-top:40px;">Top 5 Best-Selling Dishes</h3>

<table class="table">
    <thead>
        <tr>
            <th>Dish</th>
            <th>Total Sold</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $orders = $conn->query("SELECT order_items FROM orders_tbl");
        $salesCount = [];

        while ($row = $orders->fetch_assoc()) {
            $items = json_decode($row['order_items'], true);

            if (is_array($items)) {
                foreach ($items as $item) {
                    $name = $item['name'];
                    $qty  = (int)$item['qty'];

                    if (!isset($salesCount[$name])) {
                        $salesCount[$name] = 0;
                    }
                    $salesCount[$name] += $qty;
                }
            }
        }

        arsort($salesCount);
        $top5 = array_slice($salesCount, 0, 5, true);

        if (count($top5) > 0):
            foreach ($top5 as $dish => $qty):
        ?>
        <tr>
            <td><?= $dish ?></td>
            <td><?= $qty ?></td>
        </tr>
        <?php endforeach;
        else: ?>
            <tr><td colspan="2">No sales data available.</td></tr>
        <?php endif; ?>
    </tbody>
</table>


<h3 style="color:#ff9900; text-align:center; margin-top:30px;">Recent Orders</h3>

<table class="table" style="background:white; border-radius:6px; overflow:hidden;">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Order Type</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Date</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $orders = $conn->query("
            SELECT order_id, order_type, customer_name, total_price, order_date 
            FROM orders_tbl 
            $where
            ORDER BY order_id DESC
        ");

        while ($row = $orders->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['order_id'] ?></td>
            <td><?= $row['order_type'] ?></td>
            <td><?= $row['customer_name'] ?></td>
            <td>₱<?= number_format($row['total_price'], 2) ?></td>
            <td><?= $row['order_date'] ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php

$conn->close();
?>





<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php

include("connection_db.php");

$salesData = $conn->query("
    SELECT DATE(order_date) AS d, SUM(total_price) AS total
    FROM orders_tbl
    $where
    GROUP BY DATE(order_date)
    ORDER BY d ASC
");

$salesDates = [];
$salesTotals = [];
while($row = $salesData->fetch_assoc()){
    $salesDates[] = $row['d'];
    $salesTotals[] = (float)$row['total'];
}


$orderCount = $conn->query("
    SELECT DATE(order_date) AS d, COUNT(*) AS c
    FROM orders_tbl
    $where
    GROUP BY DATE(order_date)
    ORDER BY d ASC
");

$orderDates = [];
$orderTotals = [];
while($r = $orderCount->fetch_assoc()){
    $orderDates[] = $r['d'];
    $orderTotals[] = (int)$r['c'];
}


$labelsTop = array_keys($top5);
$valuesTop = array_values($top5);
?>

<hr style="border-color:#ff9900; margin:50px 0;">
<h2 style="text-align:center; color:#ff9900;">Analytics Dashboard</h2>

<div class="row mt-4">
    <div class="col-md-6">
        <canvas id="salesChart" height="250"></canvas>
    </div>
    <div class="col-md-6">
        <canvas id="topDishChart" height="250"></canvas>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-12">
        <canvas id="orderCountChart" height="200"></canvas>
    </div>
</div>

<script>

new Chart(document.getElementById("salesChart"), {
    type: "line",
    data: {
        labels: <?= json_encode($salesDates) ?>,
        datasets: [{
            label: "Daily Sales (₱)",
            data: <?= json_encode($salesTotals) ?>,
            borderWidth: 3,
            tension: 0.3
        }]
    }
});


new Chart(document.getElementById("topDishChart"), {
    type: "bar",
    data: {
        labels: <?= json_encode($labelsTop) ?>,
        datasets: [{
            label: "Total Sold",
            data: <?= json_encode($valuesTop) ?>,
            borderWidth: 1
        }]
    }
});


new Chart(document.getElementById("orderCountChart"), {
    type: "bar",
    data: {
        labels: <?= json_encode($orderDates) ?>,
        datasets: [{
            label: "Orders per Day",
            data: <?= json_encode($orderTotals) ?>,
            borderWidth: 1
        }]
    }
});
</script>
</body>
</html>
