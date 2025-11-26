<?php
session_start();

$server = "localhost";
$user = "root";
$pass = "";
$db_name = "restaurant_db";

$conn = mysqli_connect($server, $user, $pass, $db_name);

if (!$conn) {
    die("Database connection error");
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login-user.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch current user data from signup_tbl
$sql = "SELECT username, password FROM signup_tbl WHERE customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($current_username, $current_password);
$stmt->fetch();
$stmt->close();

$message = "";

// UPDATE USERNAME
if (isset($_POST['update_username'])) {
    $new_username = trim($_POST['new_username']);

    if ($new_username == "") {
        $message = "Username cannot be empty.";
    } else {
        $sql = "UPDATE signup_tbl SET username = ? WHERE customer_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_username, $user_id);
        $stmt->execute();
        $stmt->close();

        $message = "Username updated successfully!";
        $current_username = $new_username;
    }
}

// UPDATE PASSWORD
if (isset($_POST['update_password'])) {
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    // Validate old password (plain text match)
    if ($old_pass !== $current_password) {
        $message = "Old password is incorrect!";
    } elseif ($new_pass != $confirm_pass) {
        $message = "New passwords do not match!";
    } elseif (strlen($new_pass) < 6) {
        $message = "Password must be at least 6 characters!";
    } else {

        // Update password in database
        $sql = "UPDATE signup_tbl SET password = ? WHERE customer_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_pass, $user_id);
        $stmt->execute();
        $stmt->close();

        $message = "Password updated successfully!";
        $current_password = $new_pass;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>My Account</title>

<style>
body {
    background-image: url("uploads/v602-nunoon-40-rippednotes.jpg");
    background-size: cover;
    background-attachment: fixed;
    font-family: Arial;
    padding: 40px;
    color: #3d2b00;
}

.container {
    width: 450px;
    margin: auto;
    background: rgba(255, 247, 226, 0.93);
    padding: 25px;
    border-radius: 20px;
    border: 2px solid #d8b57a;
    box-shadow: 0 10px 30px rgba(0,0,0,0.25);
}

h2 {
    text-align: center;
    margin-bottom: 15px;
    color: #5a3e00;
}

label {
    font-weight: bold;
}

input {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 2px solid #c9b48a;
    margin-top: 5px;
    margin-bottom: 15px;
    background: #fff3d6;
}

button {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    background: #ffae00;
    color: white;
    border: none;
    font-weight: bold;
    cursor: pointer;
    margin-bottom: 10px;
}

button:hover {
    background: #ffbf3d;
}

.message {
    text-align: center;
    font-weight: bold;
    color: darkred;
    margin-bottom: 15px;
}

.back {
    display: block;
    text-align: center;
    margin-top: 10px;
    font-weight: bold;
    color: #5a3e00;
    text-decoration: none;
}
</style>
</head>

<body>

<div class="container">
    <h2>My Account</h2>

    <?php if ($message != "") { echo "<div class='message'>$message</div>"; } ?>

    <h3>Update Username</h3>
    <form method="POST">
        <label>Current Username:</label>
        <input type="text" value="<?php echo $current_username; ?>" disabled>

        <label>New Username:</label>
        <input type="text" name="new_username" required>

        <button type="submit" name="update_username">Update Username</button>
    </form>

    <hr style="margin: 20px 0; border: 1px solid #d8b57a;">

    <h3>Change Password</h3>
    <form method="POST">
        <label>Old Password:</label>
        <input type="password" name="old_password" required>

        <label>New Password:</label>
        <input type="password" name="new_password" required>

        <label>Confirm New Password:</label>
        <input type="password" name="confirm_password" required>

        <button type="submit" name="update_password">Update Password</button>
    </form>

    <a class="back" href="menu-header.php">⟵ Back to Menu</a>
</div>

</body>
</html>
