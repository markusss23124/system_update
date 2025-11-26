<?php
session_start();
include("connection_db.php");

if (isset($_POST['login'])) {

    $log = trim($_POST['login_id']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM signup_tbl WHERE emailaddress = ? OR username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $log, $log);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('No user found');</script>";
    } else {
        $user = $result->fetch_assoc();

       if ($user['role'] === null) {
    echo "<script>alert('Your account has no role assigned.');</script>";
}
else if (strtolower(trim($user['role'])) !== "user") {
    echo "<script>alert('This is a USER login page. Admin must log in on the admin page.');</script>";
}
else if ($password !== $user['password']) {
    echo "<script>alert('Invalid password');</script>";
}
else {
    $_SESSION['user_id'] = $user['customer_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    header("Location: menu-header.php");
    exit();
}

    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Poppins, sans-serif;
        background-image: url("brazilian-food-frame-with-copy-space.jpg");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        background-attachment: fixed;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .login-container {
        width: 380px;
        background-color: #1c1c1c;
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(255, 153, 0, 0.5);
        color: white;
        text-align: center;
    }

    .login-container img {
        width: 170px;
        height: 170px;
        border-radius: 50%;
        margin-bottom: 15px;
    }

    .login-container h1 {
        font-size: 24px;
        margin-bottom: 25px;
        font-weight: bold;
        color: #ff9900;
    }

    .login-container label {
        display: block;
        margin-top: 12px;
        font-size: 14px;
        color: #ccc;
        text-align: left;
    }

    .login-container input,
    .login-container select {
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

    .login-container input[type="submit"] {
        background-color: #ff9900;
        color: black;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }

    .login-container input[type="submit"]:hover {
        background-color: #ff7700;
    }

    .sub-text {
        font-size: 13px;
        margin-top: 10px;
        color: #ccc;
    }

    .sub-text a {
        color: #ff9900;
        text-decoration: none;
    }

    .sub-text a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>

<div class="login-container">

    <img src="ChatGPT Image Sep 20, 2025, 02_28_43 AM.png" alt="Logo">

    <h1>Welcome Customer!</h1>

    <form method="POST" autocomplete="off">

        <label>Username or Email</label>
        <input type="text" name="login_id" placeholder="Enter Username or Email" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter Password" required>

        <label>Account Type</label>
        <select name="choose" id="choose" onchange="goToLogin()">
            <option value="">-- Select Account Type --</option>
            <option value="User">User</option>
            <option value="Admin">Admin</option>
        </select>

        <input type="submit" name="login" value="Login">

        <p class="sub-text">
            Don't have an account? <a href="sign-in.php">Register</a>
        </p>

    </form>
</div>

<script>
function goToLogin() {
    let type = document.getElementById("choose").value;

    if (type === "Admin") {
        window.location.href = "login-admin.php";
    }
}
</script>

</body>
</html>
