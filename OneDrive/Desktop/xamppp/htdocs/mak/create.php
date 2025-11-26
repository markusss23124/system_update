<?php
session_start();
include("connection_db.php");

// FIXED ROLE CHECK
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== "admin") {
    header("Location: /mak/login-admin.php");
    exit();
}

$id = "";
$name = "";
$price = "";
$type = "";
$errorMessage = "";
$successMessage = "";

// GET ALL UNIQUE CATEGORIES FROM DB
$categoryResult = $conn->query("SELECT DISTINCT Food_Type FROM menu_tbl ORDER BY Food_Type ASC");
$categories = [];
while ($row = $categoryResult->fetch_assoc()) {
    $categories[] = $row['Food_Type'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = trim($_POST['id']);
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);

    // NEW: detect new category typed by user
    if ($_POST['dish_type'] === "__add_new__") {
        $type = trim($_POST['new_category']);
    } else {
        $type = trim($_POST['dish_type']);
    }

    $image = $_FILES['image']['name'];
    $temp = $_FILES['image']['tmp_name'];

    if ($id == "" || $name == "" || $price == "" || $type == "" || $image == "") {
        $errorMessage = "All fields including image are required!";
    } else {

        $uploadFolder = "uploads/";
        $uploadPath = $uploadFolder . basename($image);

        if (!is_dir($uploadFolder)) {
            mkdir($uploadFolder, 0777, true);
        }

        if (!move_uploaded_file($temp, $uploadPath)) {
            $errorMessage = "Failed to upload image!";
        } else {

            $sql = "INSERT INTO menu_tbl (dish_id, Dish_Info, Price, Food_Type, image)
                    VALUES ('$id', '$name', '$price', '$type', '$image')";

            if ($conn->query($sql)) {
                header("Location: /mak/crud-admin.php");
                exit();
            } else {
                $errorMessage = "Database error: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Dish</title>

<style>
body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background-color: #141414;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.dish-container {
    width: 420px;
    background-color: #1c1c1c;
    padding: 40px;
    border-radius: 10px;
    color: white;
    box-shadow: 0 0 25px rgba(255, 153, 0, 0.5);
    animation: fadeIn 0.7s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.dish-container h2 {
    text-align: center;
    margin-bottom: 25px;
    font-weight: bold;
    color: #ff9900;
}

.dish-container label {
    display: block;
    margin-top: 12px;
    font-size: 14px;
    color: #ddd;
}

.dish-container input,
.dish-container select {
    width: 100%;
    padding: 12px;
    margin: 8px 0 15px 0;
    font-size: 14px;
    border: none;
    border-radius: 4px;
    outline: none;
    background-color: #2b2b2b;
    color: white;
    transition: 0.2s;
}

.dish-container input:focus,
.dish-container select:focus {
    box-shadow: 0 0 8px #ff9900;
}

.dish-container input[type="submit"],
.dish-container a.btn-cancel {
    width: 48%;
    display: inline-block;
    padding: 10px;
    margin-top: 10px;
    border: none;
    border-radius: 4px;
    font-weight: bold;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
}

.dish-container input[type="submit"] {
    background-color: #ff9900;
    color: black;
    transition: 0.3s;
}

.dish-container input[type="submit"]:hover {
    background-color: #ff7700;
}

.dish-container a.btn-cancel {
    background-color: #333;
    color: #ff9900;
    margin-left: 4%;
    transition: 0.3s;
}

.dish-container a.btn-cancel:hover {
    background-color: #444;
    color: #ff7700;
}

.alert {
    font-size: 14px;
    margin-bottom: 15px;
    color: #ff4e4e;
}

#newCategoryBox {
    display: none;
}
</style>
</head>
<body>

<div class="dish-container">
    <h2>Create New Menu Item</h2>

    <?php if (!empty($errorMessage)) { ?>
        <div class="alert"><?php echo $errorMessage; ?></div>
    <?php } ?>

    <form method="POST" enctype="multipart/form-data">

        <label>Dish ID</label>
        <input type="text" name="id" value="<?php echo $id; ?>" placeholder="Enter Dish ID">

        <label>Dish Name</label>
        <input type="text" name="name" value="<?php echo $name; ?>" placeholder="Enter Dish Name">

        <label>Dish Price</label>
        <input type="text" name="price" value="<?php echo $price; ?>" placeholder="Enter Price">

        <label>Dish Type</label>
        <select name="dish_type" id="dishTypeSelect">
            <option disabled selected>Select Category</option>

            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat ?>" <?= ($type == $cat) ? "selected" : "" ?>>
                    <?= $cat ?>
                </option>
            <?php endforeach; ?>

            <option value="__add_new__">+ Add New Category</option>
        </select>

        <!-- Hidden new category input -->
        <input type="text" name="new_category" id="newCategoryBox"
               placeholder="Enter new category...">

        <label>Dish Image</label>
        <input type="file" name="image" accept="image/*">

        <input type="submit" value="Submit">
        <a href="crud-admin.php" class="btn-cancel">Cancel</a>

    </form>
</div>


<script>
document.getElementById("dishTypeSelect").addEventListener("change", function() {
    if (this.value === "__add_new__") {
        document.getElementById("newCategoryBox").style.display = "block";
    } else {
        document.getElementById("newCategoryBox").style.display = "none";
    }
});
</script>

</body>
</html>
