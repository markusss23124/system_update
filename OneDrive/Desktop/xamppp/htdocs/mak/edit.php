
<?php
session_start();
include("connection_db.php");

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== "admin") {
    header("Location: /mak/login-admin.php");
    exit();
}

$id = "";
$name = "";
$price = "";
$type = "";
$image = "";
$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (!isset($_GET['id'])) {
        header("Location: /mak/crud-admin.php");
        exit();
    }

    $id = $_GET['id'];

    $sql = "SELECT * FROM menu_tbl WHERE dish_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header("Location: /mak/crud-admin.php");
        exit();
    }

    $name = $row['Dish_Info'];
    $price = $row['Price'];
    $type = $row['Food_Type'];
    $image = $row['image'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $type = trim($_POST['dish_type']);
    $currentImage = $_POST['current_image'];

    $newImage = $_FILES['image']['name'];
    $temp = $_FILES['image']['tmp_name'];

    if ($name == "" || $price == "" || $type == "") {
        $errorMessage = "All fields except image are required.";
    } else {

        if (!empty($newImage)) {
            $uploadFolder = "uploads/";
            $uploadPath = $uploadFolder . basename($newImage);
            move_uploaded_file($temp, $uploadPath);
            $imageToSave = $newImage;
        } else {
            $imageToSave = $currentImage;
        }

        $sql = "UPDATE menu_tbl 
                SET Dish_Info=?, Price=?, Food_Type=?, image=?
                WHERE dish_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdssi", $name, $price, $type, $imageToSave, $id);

        if ($stmt->execute()) {
            $successMessage = "Dish updated successfully!";
            header("Location: /mak/crud-admin.php");
            exit();
        } else {
            $errorMessage = "Update failed: " . $conn->error;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Dish</title>
<style>
/* your same CSS */
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
    width: 400px;
    background-color: #1c1c1c;
    padding: 40px;
    border-radius: 8px;
    color: white;
    box-shadow: 0 0 20px rgba(255,153,0,0.5);
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
    color: #ccc;
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
    background-color: #333;
    color: white;
}
/* buttons */
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
}
.dish-container input[type="submit"]:hover {
    background-color: #ff7700;
}
.dish-container a.btn-cancel {
    background-color: #333;
    color: #ff9900;
}
.alert {
    font-size: 14px;
    margin-bottom: 15px;
}
</style>
</head>
<body>

<div class="dish-container">
    <h2>Edit Dish</h2>

    <?php if (!empty($errorMessage)) { ?>
        <div class="alert alert-warning"><?php echo $errorMessage; ?></div>
    <?php } ?>

    <?php if (!empty($successMessage)) { ?>
        <div class="alert alert-success"><?php echo $successMessage; ?></div>
    <?php } ?>

    <!-- FIXED: enctype added -->
    <form method="POST" enctype="multipart/form-data">

        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="hidden" name="current_image" value="<?php echo $image; ?>">

        <label>Dish Name</label>
        <input type="text" name="name" value="<?php echo $name; ?>">

        <label>Dish Price</label>
        <input type="text" name="price" value="<?php echo $price; ?>">

        <label>Dish Type</label>
        <select name="dish_type">
            <option value="Starter" <?php if($type=="Starter") echo "selected"; ?>>Starter</option>
            <option value="Main Course" <?php if($type=="Main Course") echo "selected"; ?>>Main Course</option>
            <option value="Dessert" <?php if($type=="Dessert") echo "selected"; ?>>Dessert</option>
        </select>

        <!-- FIXED: allows new image upload -->
        <label>Dish Image</label>
        <input type="file" name="image">

        <p style="font-size:12px;color:#aaa;margin-top:-5px;">
            Current: <?php echo $image; ?>
        </p>

        <input type="submit" value="Save">
        <a href="crud-admin.php" class="btn-cancel">Cancel</a>
    </form>
</div>

</body>
</html>
